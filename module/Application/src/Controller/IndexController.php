<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Application\Model\Suggestion;
use \Application\Model\Translation;

class IndexController extends AbstractActionController
{
    use Traits\ControllerConstructor;
    use Traits\ControllerMessage;

    const DEFAULT_LOCALE  = 'de_DE';

    const DEFAULT_ENTRIES_PER_PAGE = 10;

    const MESSAGE_INFO    = 'info';
    const MESSAGE_WARN    = 'warning';
    const MESSAGE_ERROR   = 'danger';
    const MESSAGE_SUCCESS = 'success';

    /**
     * @var string - current locale selected by user
     */
    private $_currentLocale = self::DEFAULT_LOCALE;

    /**
     * define locale out of query params
     * HTTP-Param: locale
     */
    public function init()
    {
        if ($this->params()->fromQuery('locale')) {
            $this->_currentLocale = $this->params()->fromQuery('locale');
        }
    }

    /**
     * translation grid page
     * HTTP-Param: filter_unclear_translation
     * HTTP-Param: file
     * HTTP-Param: rowid
     *
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        $this->init();

        // init grid
        $jumpToRow = null;
        $currentFile = null;

        // save form on user interaction
        if ($this->params()->fromPost('rowid')) {
            $jumpToRow = $this->saveIndexForm();
        }

        // prepare filter
        $currentFilterUnclear = (bool) $this->params()->fromQuery('filter_unclear_translation');

        if ($this->params()->fromQuery('file')) {
            $currentFile = $this->params()->fromQuery('file');
        }

        // prepare pagination
        $page            = (int) $this->params()->fromQuery('page') ?: 1;
        $maxPage         = 1;
        $elementsPerPage = $this->params()->fromQuery('epp') ?: self::DEFAULT_ENTRIES_PER_PAGE;

        $translationsCount = $this->_translationTable
            ->countByLanguageAndFile($this->_currentLocale, $currentFile, $currentFilterUnclear);

        if ('all' == $elementsPerPage) {
            // show all entries on one page
            $elementsPerPage = null;
        } else {
            $elementsPerPage = (int) $elementsPerPage;

            $maxPage = (int) ceil($translationsCount / $elementsPerPage);
            $maxPage = $maxPage < 1 ? 1 : $maxPage;
        }

        if ($page <= 0) {
            $page = 1;
        }

        if ($page > $maxPage) {
            $page = $maxPage;
        }

        // Prepare view
        $view =  new ViewModel([
            'supportedLocales'     => $this->_supportedLocale->fetchAll(),
            'translations'         => $this->_translationTable->fetchByLanguageAndFile($this->_currentLocale, $currentFile, $currentFilterUnclear, $elementsPerPage, $page),
            'translationBase'      => $this->_translationBaseTable->fetchAll(),
            'translationFiles'     => $this->_translationFileTable->fetchAll(),
            'translationsCount'    => $translationsCount,
            'currentLocale'        => $this->_currentLocale,
            'currentFile'          => $currentFile,
            'currentFilterUnclear' => $currentFilterUnclear,
            'currentPage'          => $page,
            'currentEPP'           => $elementsPerPage,
            'maxPages'             => $maxPage,
            'messages'             => $this->_messages,
            'jumpToRow'            => $jumpToRow,
        ]);

        return $view;
    }

    /**
     * translation detail page
     * HTTP-Param: base_id
     * HTTP-Param: row_id
     *
     * @return ViewModel
     */
    public function editAction(): ViewModel
    {
        $this->init();
        $baseId = $this->params('base_id');
        $baseTranslation = $this->_translationBaseTable->getTranslationBase($baseId);

        $request = $this->getRequest();

        if ($request->isPost()) {
            /** @var \Zend\Stdlib\Parameters $data */
            $data = $request->getPost();

            $translation = new Translation();
            $translation->exchangeArray($data->toArray());

            try {
                $modified = $this->_translationTable->saveTranslation($translation);

                if (!$modified) {
                    $this->addMessage('No changes.', self::MESSAGE_INFO);
                } else {
                    $this->addMessage('Element successfully modified', self::MESSAGE_SUCCESS);
                }
            } catch (\Exception $ex) {
                $this->addMessage($ex->getMessage(), self::MESSAGE_ERROR);
            }

//             return;
// var_dump($data, $translation);
// exit;
        }


//         // save data
//         if ($this->params()->fromPost('rowid')) {
//             // split POST params into rows
//             $formRows = [ /* rowid => [ field => value ] */ ];
//             $postParams = $this->params()->fromPost();
//             foreach ($postParams as $postKey => $postValue) {
//                 if (preg_match('@(row.{5})_(.+)@', $postKey, $matches)) {
//                     $formRows[$matches[1]][$matches[2]] = $postValue;
//                 }
//             }


//             // decide if one or all elements should be saved
//             if ('all' == $this->params()->fromPost('rowid')) {
//                 $errors = 0;
//                 $elementsModified = 0;
//                 foreach ($formRows as $row) {
//                     try {
//                         if (empty($row['suggestedTranslation'])) {
//                             continue;
//                         }
//                         $row['baseId'] = $baseTranslation->getId();
//                         $modified = $this->saveTranslationElement($row);
//                         if (false !== $modified) {
//                             $elementsModified++;
//                         }
//                     } catch(\Exception $e) {
//                         $errors++;
//                     }
//                 }

//                 if (0 < $errors) {
//                     $this->addMessage(sprintf('Error saving %d elements', $errors), self::MESSAGE_ERROR);
//                 }
//                 if (0 < $elementsModified) {
//                     $this->addMessage(sprintf('%d elements modified successfully', $elementsModified), self::MESSAGE_SUCCESS);
//                 }
//                 if (0 == $elementsModified && 0 == $errors) {
//                     $this->addMessage('No changes.', self::MESSAGE_INFO);
//                 }
//             } else {
//                 $rowId = $this->params()->fromPost('rowid');
//                 $formRows[$rowId]['baseId'] = $baseTranslation->getId();
//                 try {
//                     $success = false;
//                     if (!empty($formRows[$rowId]['suggestedTranslation'])) {
//                         $success = $this->saveTranslationElement($formRows[$rowId]);
//                     }

//                     if (false == $success) {
//                         $this->addMessage('No changes.', self::MESSAGE_INFO);
//                     } else {
//                         $this->addMessage(sprintf('Element saved successfully (element #%d)', $success), self::MESSAGE_SUCCESS);
//                     }
//                 } catch(\Exception $e) {
//                     $this->addMessage('Error saving element', self::MESSAGE_ERROR);
//                 }
//             }
//         }

        // prepare previous and next item
        $allBaseIds = $this->_translationBaseTable->fetchAll()->getIds();
        $currentKey = array_search($baseId, $allBaseIds);
        $previousKey = $currentKey - 1;
        $nextKey = $currentKey + 1;
        $maxKey = max(array_keys($allBaseIds));
        if (0 == $currentKey) {
            $previousKey = $maxKey;
        }
        if ($maxKey == $currentKey) {
            $nextKey = 0;
        }

        $supportedLocales = $this->_supportedLocale->fetchAll();
        $translations     = $this->_translationTable->fetchByBaseId($baseId)->groupByLocales($supportedLocales);

        return new ViewModel([
            'supportedLocales'       => $supportedLocales,
            'currentLocale'          => $this->_currentLocale,
            'currentTranslationFile' => $this->_translationFileTable->getTranslationFile($baseTranslation->getFileId())->getFilename(),
            'messages'               => $this->_messages,
            'baseTranslation'        => $baseTranslation,
            'translations'           => $translations,
            'suggestions'            => $this->_suggestionTable->fetchByTranslationId($translations[$this->_currentLocale]->getId()),
            'previousItemId'         => $allBaseIds[$previousKey],
            'nextItemId'             => $allBaseIds[$nextKey],
        ]);
    }

    /**
     * save suggestions of index form (all or just a single element)
     *
     * @return int|null - if we do a single insert we can jump to this id of row
     */
    private function saveIndexForm()
    {
        $jumpToRow = null;

        // split POST params into rows
        $formRows = [ /* rowid => [field => value] */ ];
        $postParams = $this->params()->fromPost();
        foreach ($postParams as $postKey => $postValue) {
            if (preg_match('@(row\d+)_(.+)@', $postKey, $matches)) {
                $formRows[$matches[1]][$matches[2]] = $postValue;
            }
        }

        // decide if one or all elements should be saved
        if ('all' == $this->params()->fromPost('rowid')) {
            $errors = 0;
            $elementsModified = 0;
            foreach ($formRows as $row) {
                if (empty($row['suggestedTranslation'])) {
                    continue;
                }
                try {
                    $modified = $this->addSuggestion((int)$row['translationId'], $row['suggestedTranslation']);
                    if (false !== $modified) {
                        $elementsModified++;
                    }
                } catch(\Exception $e) {
                    $errors++;
                }
            }

            if (0 < $errors) {
                $this->addMessage(sprintf('Error saving %d elements', $errors), self::MESSAGE_ERROR);
            }
            if (0 < $elementsModified) {
                $this->addMessage(sprintf('%d elements saved successfully', $elementsModified), self::MESSAGE_SUCCESS);
            }
            if (0 == $elementsModified && 0 == $errors) {
                $this->addMessage('No changes.', self::MESSAGE_INFO);
            }
        } else {
            $rowId = $this->params()->fromPost('rowid');
            $jumpToRow = $rowId;
            try {
                $success = false;
                if (!empty($formRows[$rowId]['suggestedTranslation'])) {
                    $success = $this->addSuggestion((int)$formRows[$rowId]['translationId'], $formRows[$rowId]['suggestedTranslation']);
                }

                if (false == $success) {
                    $this->addMessage('No changes.', self::MESSAGE_INFO);
                } else {
                    $this->addMessage(sprintf('Element saved successfully (element #%d)', $success), self::MESSAGE_SUCCESS);
                }
            } catch(\Exception $e) {
                $this->addMessage('Error saving element', self::MESSAGE_ERROR);
            }
        }

        return $jumpToRow;
    }

    /**
     * Save translation element with given data.
     *
     * @param array $element Translation element data
     *
     * @return int|false ID of saved element
     */
    private function saveTranslationElement(array $element)
    {
        if (!array_key_exists('unclearTranslation', $element)) {
            $element['unclearTranslation'] = 0;
        }

        $translation = null;

        $data = [
            'translation_id'      => $element['id'],
            'base_id'             => $element['baseId'],
            'locale'              => $element['locale'],
            'current_translation' => $element['suggestedTranslation'],
            'unclear_translation' => $element['unclearTranslation'],
        ];

        if (isset($element['id'])) {
            $translation = $this->_translationTable->getTranslation($element['id']); // $element['translation_id']
        }

        if (!($translation instanceof Translation)) {
            $translation = new Translation();
        }

        $translation->exchangeArray($data);

        return $this->_translationTable->saveTranslation($translation);
    }

    /**
     * Add a new suggestion
     *
     * @param int    $translationId ID of translation
     * @param string $content       Content of the suggestion
     *
     * @return bool
     */
    private function addSuggestion(int $translationId, string $content): bool
    {
        $suggestion = new Suggestion([
            'id'            => null,
            'translationId' => (int) $translationId,
            'suggestion'    => $content,
        ]);

        return (bool) $this->_suggestionTable->saveSuggestion($suggestion);
    }
}

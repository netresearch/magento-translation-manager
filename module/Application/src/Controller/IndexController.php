<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Application\Model\Suggestion;
use \Application\Model\Translation;

class IndexController extends AbstractActionController implements ControllerInterface
{
    use Traits\ControllerConstructor;

    const DEFAULT_LOCALE           = 'de_DE';
    const DEFAULT_ENTRIES_PER_PAGE = 10;

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
     * HTTP-Param: unclear
     * HTTP-Param: file
     * HTTP-Param: rowid
     *
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        $this->init();

        $jumpToRow   = null;
        $currentFile = null;

        // save form on user interaction
//         if ($this->params()->fromPost('rowid')) {
//             $jumpToRow = $this->saveIndexForm();
//         }

        // prepare filter
        $currentFilterUnclear = (bool) $this->params()->fromQuery('unclear');

        if ($this->params()->fromQuery('file')) {
            $currentFile = $this->params()->fromQuery('file');
        }

        // prepare pagination
        $page            = $this->params()->fromQuery('page', 1);
        $elementsPerPage = $this->params()->fromQuery('epp', self::DEFAULT_ENTRIES_PER_PAGE);

        $translationPaginator = $this->_translationTable->fetchByLanguageAndFile($this->_currentLocale, $currentFile, $currentFilterUnclear);

        $translationPaginator->setCurrentPageNumber($page)
            ->setItemCountPerPage($elementsPerPage)
            ->setPageRange(11);

        // Prepare view
        $view =  new ViewModel([
            'translations'         => $translationPaginator,
            'supportedLocales'     => $this->_supportedLocale->fetchAll(),
            'translationBase'      => $this->_translationBaseTable->fetchAll(),
            'translationFiles'     => $this->_translationFileTable->fetchAll(),
            'currentLocale'        => $this->_currentLocale,
            'currentFile'          => $currentFile,
            'currentFilterUnclear' => $currentFilterUnclear,
            'currentPage'          => $page,
            'currentEPP'           => $elementsPerPage,
            'jumpToRow'            => $jumpToRow,
        ]);

        return $view;
    }

    /**
     * Get the previous base id starting from the given one.
     *
     * @param int $baseId Base translation record id
     *
     * @return int|bool FALSE if there is no previous key
     */
    private function getPreviousBaseId(int $baseId)
    {
        $allBaseIds  = $this->_translationBaseTable->fetchAll()->getIds();
        $minKey      = min(array_keys($allBaseIds));
        $currentKey  = array_search($baseId, $allBaseIds);
        $previousKey = $currentKey === false ? 0 : ($currentKey - 1);
        $previousKey = max($previousKey, $minKey);

        if ($currentKey === $previousKey) {
            return false;
        }

        return $allBaseIds[$previousKey];
    }

    /**
     * Get the next base id starting from the given one.
     *
     * @param int $baseId Base translation record id
     *
     * @return int|bool FALSE if there is no next key
     */
    private function getNextBaseId(int $baseId)
    {
        $allBaseIds = $this->_translationBaseTable->fetchAll()->getIds();
        $maxKey     = max(array_keys($allBaseIds));
        $currentKey = array_search($baseId, $allBaseIds);
        $nextKey = $currentKey === false ? $maxKey : ($currentKey + 1);
        $nextKey = min($nextKey, $maxKey);

        if ($currentKey === $nextKey) {
            return false;
        }

        return $allBaseIds[$nextKey];
    }

    /**
     * translation detail page
     * HTTP-Param: baseId
     * HTTP-Param: row_id
     *
     * @return mixed
     */
    public function editAction()
    {
        $this->init();

        try {
            $baseId          = $this->params('baseId');
            $baseTranslation = $this->_translationBaseTable->getTranslationBase($baseId);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addErrorMessage($ex->getMessage());

            // Redirect to start page
            return $this->redirect()->toRoute('home');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            /** @var \Zend\Stdlib\Parameters $data */
            $data = $request->getPost();

            if ($translations = $data->get('translations', [])) {
                $modifiedCount = 0;

                foreach ($translations as $newTranslation) {
                    $translation = new Translation();
                    $translation->exchangeArray($newTranslation);

                    try {
                        $modified = $this->_translationTable->saveTranslation($translation);

                        if ($modified !== false) {
                            ++$modifiedCount;
                        }
                    } catch (\Exception $ex) {
                        // Ignore empty translations
                        if ($ex->getCode() !== 1000) {
                            $this->flashMessenger()->addErrorMessage($ex->getMessage());
                        }
                    }
                }

                if ($modifiedCount) {
                    $this->flashMessenger()->addSuccessMessage(
                        sprintf('Successfully modified %d element(s)', $modifiedCount)
                    );
                } else {
                    if (!$modifiedCount) {
                        $this->flashMessenger()->addInfoMessage('No changes');
                    }
                }
            }

            // Redirect to next translation record on success
//             return $this->redirect()->toRoute(
//                 'index',
//                 [
//                     'action' => 'edit',
//                     'baseId' => $this->getNextBaseId($baseId),
//                 ],
//                 [
//                     'query' => [
//                         'locale' => $data['locale']
//                     ]
//                 ]
//             );
        }

        $supportedLocales = $this->_supportedLocale->fetchAll();
        $translations     = $this->_translationTable->fetchByBaseId($baseId)->groupByLocales($supportedLocales);

        return new ViewModel([
            'supportedLocales'       => $supportedLocales,
            'currentLocale'          => $this->_currentLocale,
            'currentTranslationFile' => $this->_translationFileTable->getTranslationFile($baseTranslation->getFileId())->getFilename(),
            'baseTranslation'        => $baseTranslation,
            'translations'           => $translations,
            //'suggestions'            => $this->_suggestionTable->fetchByTranslationId($translations[$this->_currentLocale]->getId()),
            'previousItemId'         => $this->getPreviousBaseId($baseId),
            'nextItemId'             => $this->getNextBaseId($baseId),
        ]);
    }

//     /**
//      * save suggestions of index form (all or just a single element)
//      *
//      * @return int|null - if we do a single insert we can jump to this id of row
//      */
//     private function saveIndexForm()
//     {
//         $jumpToRow = null;

//         // split POST params into rows
//         $formRows = [ /* rowid => [field => value] */ ];
//         $postParams = $this->params()->fromPost();
//         foreach ($postParams as $postKey => $postValue) {
//             if (preg_match('@(row\d+)_(.+)@', $postKey, $matches)) {
//                 $formRows[$matches[1]][$matches[2]] = $postValue;
//             }
//         }

//         // decide if one or all elements should be saved
//         if ('all' == $this->params()->fromPost('rowid')) {
//             $errors = 0;
//             $elementsModified = 0;
//             foreach ($formRows as $row) {
//                 if (empty($row['suggestedTranslation'])) {
//                     continue;
//                 }
//                 try {
//                     $modified = $this->addSuggestion((int)$row['translationId'], $row['suggestedTranslation']);
//                     if (false !== $modified) {
//                         $elementsModified++;
//                     }
//                 } catch(\Exception $e) {
//                     $errors++;
//                 }
//             }

//             if (0 < $errors) {
//                 $this->flashMessenger()->addErrorMessage(sprintf('Error saving %d elements', $errors));
//             }
//             if (0 < $elementsModified) {
//                 $this->flashMessenger()->addSuccessMessage(sprintf('%d elements saved successfully', $elementsModified));
//             }
//             if (0 == $elementsModified && 0 == $errors) {
//                 $this->flashMessenger()->addInfoMessage('No changes.');
//             }
//         } else {
//             $rowId = $this->params()->fromPost('rowid');
//             $jumpToRow = $rowId;
//             try {
//                 $success = false;
//                 if (!empty($formRows[$rowId]['suggestedTranslation'])) {
//                     $success = $this->addSuggestion((int)$formRows[$rowId]['translationId'], $formRows[$rowId]['suggestedTranslation']);
//                 }

//                 if (false == $success) {
//                     $this->flashMessenger()->addInfoMessage('No changes.');
//                 } else {
//                     $this->flashMessenger()->addSuccessMessage(sprintf('Element saved successfully (element #%d)', $success));
//                 }
//             } catch(\Exception $e) {
//                 $this->flashMessenger()->addErrorMessage('Error saving element');
//             }
//         }

//         return $jumpToRow;
//     }

//     /**
//      * Save translation element with given data.
//      *
//      * @param array $element Translation element data
//      *
//      * @return int|false ID of saved element
//      */
//     private function saveTranslationElement(array $element)
//     {
//         if (!array_key_exists('unclearTranslation', $element)) {
//             $element['unclearTranslation'] = 0;
//         }

//         $translation = null;

//         $data = [
//             'translation_id'      => $element['id'],
//             'baseId'              => $element['baseId'],
//             'locale'              => $element['locale'],
//             'current_translation' => $element['suggestedTranslation'],
//             'unclear_translation' => $element['unclearTranslation'],
//         ];

//         if (isset($element['id'])) {
//             $translation = $this->_translationTable->getTranslation($element['id']); // $element['translation_id']
//         }

//         if (!($translation instanceof Translation)) {
//             $translation = new Translation();
//         }

//         $translation->exchangeArray($data);

//         return $this->_translationTable->saveTranslation($translation);
//     }

//     /**
//      * Add a new suggestion
//      *
//      * @param int    $translationId ID of translation
//      * @param string $content       Content of the suggestion
//      *
//      * @return bool
//      */
//     private function addSuggestion(int $translationId, string $content): bool
//     {
//         $suggestion = new Suggestion([
//             'id'            => null,
//             'translationId' => (int) $translationId,
//             'suggestion'    => $content,
//         ]);

//         return (bool) $this->_suggestionTable->saveSuggestion($suggestion);
//     }
}

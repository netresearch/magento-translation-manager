<?php
namespace Import\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Application\Controller\ControllerInterface;
use \Application\Controller\Traits;
use \Application\Model\LocaleTable;
use \Application\Model\TranslationTable;
use \Application\Model\TranslationBaseTable;
use \Application\Model\TranslationFileTable;
use \Application\Model\Translation;
use \Application\Model\TranslationBase;
use \Application\Model\TranslationFile;
use \Import\Form\ImportForm;

class ImportController extends AbstractActionController implements ControllerInterface
{
    /**
     * @var LocaleTable
     */
    private $localeTable;

    /**
     * @var TranslationTable
     */
    private $translationTable;

    /**
     * @var TranslationBaseTable
     */
    private $translationBaseTable;

    /**
     * @var TranslationFileTable
     */
    private $translationFileTable;

    public function __construct(
        LocaleTable          $localeTable,
        TranslationTable     $translationTable,
        TranslationBaseTable $translationBaseTable,
        TranslationFileTable $translationFileTable
    ) {
        $this->localeTable          = $localeTable;
        $this->translationTable     = $translationTable;
        $this->translationBaseTable = $translationBaseTable;
        $this->translationFileTable = $translationFileTable;
    }

    /**
     * Action "index".
     *
     * @return mixed
     */
    public function indexAction()
    {
        $form    = new ImportForm($this->localeTable->fetchAll());
        $request = $this->getRequest();

        if ($request->isPost()) {
            // Make certain to merge the files info
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data   = $form->getData();
                $locale = $data['locale'];

                foreach ($data['files'] as $file) {
                    try {
                        $fileRecord = $this->translationFileTable->fetchByFilename($file['name']);
                        $fileId     = $fileRecord->getId();
                    } catch (\Exception $ex) {
                        // Add new translation file record
                        $translationFile = new TranslationFile();
                        $translationFile->setFilename($file['name']);

                        $fileId = $this->translationFileTable->saveTranslationFile($translationFile);
                    }

                    // Read file
                    $file = new \SplFileObject($file['tmp_name']);
                    $file->setFlags(
                            \SplFileObject::READ_CSV
                            | \SplFileObject::READ_AHEAD
                            | \SplFileObject::SKIP_EMPTY
                            | \SplFileObject::DROP_NEW_LINE
                    );

                    foreach ($file as $row) {
                        // Avoid "Undefined offset" errors by padding the array
                        list($baseValue, $translatedValue) = array_pad($row, 2, null);

                        if (empty($baseValue)) {
                            continue;
                        }

                        try {
                            $baseRecord = $this->translationBaseTable->fetchByOriginSourceAndFileId($baseValue, $fileId);
                            $baseId     = $baseRecord->getId();
                        } catch (\Exception $ex) {
                            // Add new base translation record
                            $baseRecord = new TranslationBase();
                            $baseRecord->setOriginSource($baseValue)
                                ->setFileId($fileId);

                            $baseId = $this->translationBaseTable->saveTranslationBase($baseRecord);
                        }

                        $translationRecord = $this->translationTable->fetchByBaseIdAndLocale($baseId, $locale);

                        // No record found, and translation differs from base value
                        if (!$translationRecord->count()
                            && ($baseValue !== $translatedValue)
                        ) {
                            // Add new translation record
                            $translation = new Translation();
                            $translation->setBaseId($baseId)
                                ->setLocale($locale)
                                ->setUnclear(false)
                                ->setTranslation($translatedValue);

                            try {
                                $this->translationTable->saveTranslation($translation);
                            } catch (\Exception $ex) {
                                // Ignore empty translations
                                if ($ex->getCode() !== 1000) {
                                    $this->flashMessenger()->addErrorMessage($ex->getMessage());
                                }
                            }
                        }
                    }
                }

                $this->flashMessenger()->addSuccessMessage('Import successfully done');
            }
        }

        return [
            'form' => $form,
        ];
    }
}

<?php
namespace Import\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Import\Form\ImportForm;
use \Application\Model\LocaleTable;
use \Application\Model\TranslationTable;
use \Application\Model\TranslationBaseTable;
use \Application\Model\TranslationFileTable;
use \Application\Model\Locale;
use \Application\Model\Translation;
use \Application\Model\TranslationBase;
use \Application\Model\TranslationFile;

class ImportController extends AbstractActionController
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
     * Loads the CSV file into array. Removes empty lines.
     */
    private function loadCsv($filename)
    {
        $csv = array_map('str_getcsv', file($filename));
        return array_filter($csv, function ($value) { return !empty($value[0]); });
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
                    $csv = $this->loadCsv($file['tmp_name']);

                    foreach ($csv as $entry) {
                        $baseValue       = $entry[0];
                        $translatedValue = $entry[1];

                        try {
                            $baseRecord = $this->translationBaseTable->fetchByOriginSource($baseValue);
                            $baseId     = $baseRecord->getId();
                        } catch (\Exception $ex) {
                            // Add new base translation record
                            $baseRecord = new TranslationBase();
                            $baseRecord->setOriginSource($baseValue)
                                ->setFileId($fileId);

                            $baseId = $this->translationBaseTable->saveTranslationBase($baseRecord);
                        }

                        $translationRecord = $this->translationTable->fetchByBaseId($baseId);

                        if (!$translationRecord->count()) {
                            // Add new translation record
                            $translation = new Translation();
                            $translation->setBaseId($baseId)
                                ->setLocale($locale)
                                ->setUnclear(true)
                                ->setTranslation($translatedValue);

                            $this->translationTable->saveTranslation($translation);
                        }
                    }
                }

                // Redirect to home
//                 return $this->redirect()->toRoute('home');
            }
        }

        return [
            'form' => $form,
        ];
    }
}

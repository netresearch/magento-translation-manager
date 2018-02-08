<?php
namespace Export\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Application\Controller\ControllerInterface;
use \Application\Controller\Traits;
use \Application\Model\LocaleTable;
use \Application\Model\TranslationTable;
use \Application\Model\TranslationBaseTable;
use \Application\Model\TranslationFileTable;
use \Application\Model\Locale;
use \Application\Model\Translation;
use \Application\Model\TranslationBase;
use \Application\Model\TranslationFile;
use \Export\Form\ExportForm;

class ExportController extends AbstractActionController implements ControllerInterface
{
    use Traits\ControllerMessage;
    use Traits\ControllerConstructor;

    const EXPORT_PATH = 'public/csv-export/';

    /**
     * @var LocaleTable
     */
    protected $localeTable;

    /**
     * @var TranslationTable
     */
    protected $translationTable;

    /**
     * @var TranslationBaseTable
     */
    protected $translationBaseTable;

    /**
     * @var TranslationFileTable
     */
    protected $translationFileTable;

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
     * Get instance of export form.
     *
     * @return ExportForm
     */
    protected function getFormInstance(): ExportForm
    {
        return new ExportForm($this->translationFileTable->fetchAll(), $this->localeTable->fetchAll());
    }

    protected function performExport(array $formData): array
    {
        foreach ($formData['locales'] as $locale) {
            foreach ($formData['files'] as $fileName) {
                // Get all translations
                $translations = $this->translationTable->fetchByLanguageAndFile($locale, $fileName, false, null);

                // prepare file to output in export folder
                $outputDirectory = self::EXPORT_PATH . "$locale/";

                if (!is_dir($outputDirectory)) {
                    mkdir($outputDirectory, 0777, true);
                }

                $outputFile = fopen($outputDirectory . $fileName, 'w');

                /** @var Translation $translation */
                foreach ($translations as $translation) {
                    fputcsv(
                        $outputFile,
                        [
                            $translation->getTranslationBase()->getOriginSource(),
                            empty($translation->getTranslation())
                            ? $translation->getTranslationBase()->getOriginSource()
                            : $translation->getTranslation()
                        ],
                        ',',
                        '"'
                        );
                }

                fclose($outputFile);

                // store download filenames for template
                $downloadFiles[] = [
                    'path'     => '/' . self::EXPORT_PATH . "$locale/" . $fileName,
                    'locale'   => $locale,
                    'filename' => $fileName,
                ];
            }
        }

        return $downloadFiles;
    }

    /**
     * Action "index".
     *
     * @return mixed
     */
    public function indexAction()
    {
        $form    = $this->getFormInstance();
        $request = $this->getRequest();

        $downloadFiles = [];

        if ($request->isPost()) {
            $form->setData($request->getPost()->toArray());

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $formData      = $form->getData();
                $downloadFiles = $this->performExport($formData);
            }
        }

        return new ViewModel([
            'form'             => $form,
            'translationFiles' => $this->translationFileTable->fetchAll(),
            'supportedLocales' => $this->localeTable->fetchAll(),
            'downloadFiles'    => $downloadFiles,
        ]);
    }
}

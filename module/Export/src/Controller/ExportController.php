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
use \Application\ResultSet\Translation as ResultSet_Translation;
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
    protected function getFormInstance()
    {
        return new ExportForm($this->translationFileTable->fetchAll(), $this->localeTable->fetchAll());
    }

    /**
     * Get the translated string or the original source string if no translation is available.
     *
     * @param Translation $translation Translation record instance
     *
     * @return string
     */
    public function getTranslatedString(Translation $translation)
    {
        return empty($translation->getTranslation())
            ? $translation->getTranslationBase()->getOriginSource()
            : $translation->getTranslation();
    }

    /**
     * Write an translation record to CSV file
     *
     * @param resource    $outputFile  Output file handle
     * @param Translation $translation Translation record instance
     * @param string      $delimiter   Character used as delimiter
     * @param string      $enclosure   Character used as enclosure
     *
     * @return void
     */
    public function writeCsv($outputFile, Translation $translation, $delimiter = ',', $enclosure = '"')
    {
        fputcsv(
            $outputFile,
            [
                $translation->getTranslationBase()->getOriginSource(),
                $this->getTranslatedString($translation),
            ],
            $delimiter,
            $enclosure
        );
    }

    /**
     * Get file iterator instance.
     *
     * @param array $files List of selected file names
     *
     * @return \ArrayIterator
     */
    public function getFileIterator(array $files): \ArrayIterator
    {
        return new \ArrayIterator($files);
    }

    /**
     * Get translations from database.
     *
     * @param string $locale   Locale used to fetch translations from
     * @param string $fileName File name used to fetch translations from
     *
     * @return ResultSet_Translation
     */
    public function getTranslations(string $locale, ?string $fileName): ResultSet_Translation
    {
        return $this->translationTable->fetchByLanguageAndFile($locale, $fileName, false, null);
    }

    /**
     *
     * @param array $formData Submitted form data
     *
     * @return array
     */
    protected function performExport(array $formData): array
    {
        foreach ($formData['locales'] as $locale) {
            $it = $this->getFileIterator($formData['files']);

            while ($it->valid()) {
                $fileName = $it->current();

                // Get all translations
                $translations = $this->getTranslations($locale, $fileName);

                // Prepare file to output in export folder
                $outputDirectory = self::EXPORT_PATH . "$locale/";

                if (!is_dir($outputDirectory)) {
                    mkdir($outputDirectory, 0777, true);
                }

                $outputFile = fopen($outputDirectory . $fileName, 'w');

                /** @var Translation $translation */
                foreach ($translations as $translation) {
                    $this->writeCsv($outputFile, $translation);
                }

                fclose($outputFile);

                // Store download filenames for template
                $downloadFiles[] = [
                    'path'     => '/' . self::EXPORT_PATH . "$locale/" . $fileName,
                    'locale'   => $locale,
                    'filename' => $fileName,
                ];

                $it->next();
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

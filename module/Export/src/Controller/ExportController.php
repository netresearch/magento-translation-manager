<?php
namespace Export\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Zend\Http\Response\Stream;
use \Zend\Http\Headers;
use \Application\Controller\ControllerInterface;
use \Application\Controller\Traits;
use \Application\Model\LocaleTable;
use \Application\Model\TranslationTable;
use \Application\Model\TranslationFileTable;
use \Application\Model\Translation;
use \Export\Form\ExportForm;

class ExportController extends AbstractActionController implements ControllerInterface
{
    const EXPORT_PATH = '/data/export/';

    /**
     * @var LocaleTable
     */
    protected $localeTable;

    /**
     * @var TranslationTable
     */
    protected $translationTable;

    /**
     * @var TranslationFileTable
     */
    protected $translationFileTable;

    /**
     * Constructor.
     *
     * @param LocaleTable          $localeTable          Locale table instance
     * @param TranslationTable     $translationTable     Translation table instance
     * @param TranslationFileTable $translationFileTable Translation file table instance
     */
    public function __construct(
        LocaleTable          $localeTable,
        TranslationTable     $translationTable,
        TranslationFileTable $translationFileTable
    ) {
        $this->localeTable          = $localeTable;
        $this->translationTable     = $translationTable;
        $this->translationFileTable = $translationFileTable;
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
    protected function getTranslatedString(Translation $translation)
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
    protected function writeCsv($outputFile, Translation $translation, $delimiter = ',', $enclosure = '"')
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
     * Create output directory if it not exists and return it.
     *
     * @param string $locale Locale used to fetch translations from
     *
     * @return string
     */
    protected function createOutputDirectory(string $locale): string
    {
        // Prepare export folder
        $outputDirectory = getcwd() . self::EXPORT_PATH . "$locale/";

        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        return $outputDirectory;
    }

    /**
     * Loads records from database and write them to the specified output file.
     *
     * @param resource $outputFile Output file handle
     * @param string   $locale     Locale used to fetch translations from
     * @param string   $fileName   File name used to fetch translations from
     *
     * @return void
     */
    protected function writeCsvRecords($outputFile, string $locale, string $fileName)
    {
        // Get all translations
        $translationsPaginator = $this->translationTable->fetchByLanguageAndFile($locale, $fileName);
        $translationsPaginator->setItemCountPerPage(250)
            ->setCurrentPageNumber(1);

        $totalPages = $translationsPaginator->getPages()->last;

        // Iterate over all pages and write records to CSV file
        for ($page = 1; $page <= $totalPages; ++$page) {
            $translationsPaginator->setCurrentPageNumber($page);

            /** @var Translation $translation */
            foreach ($translationsPaginator as $translation) {
                $this->writeCsv($outputFile, $translation);
            }
        }
    }

    /**
     * Perform the export.
     *
     * @param array $formData Submitted form data
     *
     * @return array
     */
    protected function performExport(array $formData): array
    {
        foreach ($formData['locales'] as $locale) {
            // Prepare export folder
            $outputDirectory = $this->createOutputDirectory($locale);

            foreach ($formData['files'] as $fileName) {
                // Create export file for each selected file record
                $outputFile = fopen($outputDirectory . $fileName, 'w');

                // Write all records of same type to same file
                $this->writeCsvRecords($outputFile, $locale, $fileName);

                fclose($outputFile);

                // Store download filenames for template
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
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        $form    = $this->getFormInstance();
        $request = $this->getRequest();

        $view = new ViewModel([
            'form'             => $form,
            'translationFiles' => $this->translationFileTable->fetchAll(),
            'supportedLocales' => $this->localeTable->fetchAll(),
        ]);

        // See "template_map" in "module.config.php"
        $view->setTemplate('export');

        $downloadFiles = [];

        if ($request->isPost()) {
            $form->setData($request->getPost()->toArray());

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $formData      = $form->getData();
                $downloadFiles = $this->performExport($formData);

                $view->setTemplate('download')
                    ->setVariable('downloadFiles', $downloadFiles);

                // Add dummy breadcrumb entry for download page
                $this->breadcrumb()->addBreadcrumb(
                    'export',
                    [
                        'uri'    => $this->getRequest()->getRequestUri(),
                        'label'  => 'Download',
                        'active' => true,
                    ]
                );
            }
        }

        return $view;
    }

    /**
     * Action "download".
     *
     * @return mixed
     */
    public function downloadAction()
    {
        $file           = $this->params()->fromQuery('file');
        $locale         = $this->params()->fromQuery('locale');
        $fileToDownload = realpath(getcwd() . self::EXPORT_PATH . $locale . '/' . $file);

        if (!file_exists($fileToDownload)
            || (strpos($fileToDownload, self::EXPORT_PATH) === false)
        ) {
            $this->flashMessenger()->addErrorMessage('File "' . $file . '" not found');
            return $this->redirect()->toRoute('export');
        }

        $response = new Stream();
        $response->setStream(fopen($fileToDownload, 'r'))
            ->setStatusCode(200)
            ->setStreamName(basename($fileToDownload));

        $headers = new Headers();
        $headers->addHeaders([
            'Content-Disposition' => 'attachment; filename="' . basename($fileToDownload) .'"',
            'Content-Type'        => 'application/octet-stream',
            'Content-Length'      => filesize($fileToDownload),
            'Expires'             => '@0', // @0, because zf2 parses date as string to \DateTime() object
            'Cache-Control'       => 'must-revalidate',
            'Pragma'              => 'public'
        ]);

        $response->setHeaders($headers);

        return $response;
    }

    /**
     * Action "compress".
     *
     * @return mixed
     */
    public function compressAction()
    {
        $this->flashmessenger()->addInfoMessage('Zip download not yet implemented');

        return $this->redirect()->toRoute('export');

        // TODO
        //$zip = new \ZipArchive();
    }
}

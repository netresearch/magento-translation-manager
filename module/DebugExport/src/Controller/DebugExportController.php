<?php
namespace DebugExport\Controller;

use \Zend\View\Model\ViewModel;
use \Application\Model\Translation;
use \Export\Controller\ExportController;
use \DebugExport\Form\DebugExportForm;

class DebugExportController extends ExportController
{
    const MASTER_FILENAME = 'MasterFile.csv';

    /**
     * @var bool
     */
    private $masterFile = false;

    /**
     * @var bool
     */
    private $debugTranslations = false;

    /**
     * Get instance of export form.
     *
     * @return DebugExportForm
     */
    protected function getFormInstance()
    {
        return new DebugExportForm($this->translationFileTable->fetchAll(), $this->localeTable->fetchAll());
    }

    /**
     * Get the file name of origin file of a translation record.
     *
     * @param int $fileId File record id
     *
     * @return string
     */
    private function getFileName(int $fileId): string
    {
        return $this->translationFileTable->getTranslationFile($fileId)->getFilename();
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
        if ($this->debugTranslations) {
            return sprintf(
                'DEBUG-ID_%d_%s_%s',
                $translation->getBaseId(),
                $this->getFileName($translation->getTranslationBase()->getFileId()),
                empty($translation->getTranslation()) ? $translation->getTranslationBase()->getOriginSource() : $translation->getTranslation()
            );
        }

        return parent::getTranslatedString($translation);
    }

    /**
     *
     * @param array $formData Submitted form data
     *
     * @return array
     */
    protected function performExport(array $formData): array
    {
        $this->masterFile        = (bool) $formData['masterFile'];
        $this->debugTranslations = (bool) $formData['debugTranslations'];

        if (!$this->masterFile) {
            return parent::performExport($formData);
        }

        foreach ($formData['locales'] as $locale) {
            // Prepare export folder
            $outputDirectory = $this->createOutputDirectory($locale);

            // Create a single export file for each selected file record
            $outputFile = fopen($outputDirectory . self::MASTER_FILENAME, 'w');

            // Write everything in one file
            foreach ($formData['files'] as $fileName) {
                $this->writeCsvRecords($outputFile, $locale, $fileName);
            }

            fclose($outputFile);

            // Store download filenames for template
            $downloadFiles[] = [
                'path'     => '/' . self::EXPORT_PATH . "$locale/" . self::MASTER_FILENAME,
                'locale'   => $locale,
                'filename' => self::MASTER_FILENAME,
            ];
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
        $view = parent::indexAction();
        $form = $view->getVariable('form');

        $debugView = new ViewModel([
            'form' => $form,
        ]);

        // See "template_map" in "module.config.php"
        $debugView->setTemplate('debug');

        // Add child template to view
        $view->addChild($debugView, 'debugView');

        return $view;
    }
}

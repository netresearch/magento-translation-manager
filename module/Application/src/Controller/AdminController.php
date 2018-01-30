<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
    use Traits\ControllerConstructor;

    const EXPORT_PATH = 'export/';

    /**
     * Admin dashboard
     *
     * @return ViewModel
     */
    public function indexAction(): ViewModel
    {
        // prepare view
        $view =  new ViewModel([
            'translationFiles' => $this->_translationFileTable->fetchAll(),
            'supportedLocales' => $this->_supportedLocale->fetchAll(),
        ]);

        return $view;
    }

    /**
     * Admin dashboard
     *
     * @return ViewModel
     */
    public function importAction(): ViewModel
    {
        // prepare view
        $view =  new ViewModel([
        ]);

        return $view;
    }

    /**
     * export language files as CSV data
     * HTTP-Param: translation_file
     * HTTP-Param: locale
     *
     * @return ViewModel
     */
    public function exportAction(): ViewModel
    {
        $downloadFiles = [];

        $exportFile = $this->params()->fromPost('translation_file');
        if (!$exportFile) {
            $exportFile = [];
        }
        $exportLocale = $this->params()->fromPost('locale');
        if (!$exportLocale) {
            $exportLocale = [];
        }

        $translationBase = $this->_translationBaseTable->fetchAll();

        foreach ($exportLocale as $locale) {
            foreach ($exportFile as $fileName) {
                $translations = $this->_translationTable->fetchByLanguageAndFile($locale, $fileName);

                // prepare file to output in export folder
                $outputDirectory = 'public/' . self::EXPORT_PATH . "$locale/";
                if (!is_dir($outputDirectory)) {
                    mkdir($outputDirectory);
                }
                $outputFile = fopen($outputDirectory . $fileName, 'w');
                    foreach ($translations as $translation) {
                         /* @var $translation \Application\Model\Translation */
                         /* @var $base \Application\Model\TranslationBase */
                        $base = $translationBase[$translation['base_id']];
                        fputcsv(
                            $outputFile,
                            [
                                $base->getOriginSource(), $translation['current_translation']
                            ],
                            ',', '"'
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

        return new ViewModel([
            'downloadFiles' => $downloadFiles, /* [path|locale|filename] => string */
        ]);
    }
}

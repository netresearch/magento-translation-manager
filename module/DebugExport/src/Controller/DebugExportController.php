<?php
namespace DebugExport\Controller;

use \Zend\View\Model\ViewModel;
use \Application\Model\LocaleTable;
use \Application\Model\TranslationTable;
use \Application\Model\TranslationBaseTable;
use \Application\Model\TranslationFileTable;
use \Application\Model\Locale;
use \Application\Model\Translation;
use \Application\Model\TranslationBase;
use \Application\Model\TranslationFile;
use \Application\ResultSet\Translation as ResultSet_Translation;
use \Export\Controller\ExportController;
use \DebugExport\Form\DebugExportForm;

class DebugExportController extends ExportController
{
    /**
     *
     * @var \Export\Module
     */
    private $exportModule;

    public function __construct(
        $exportModule,
        LocaleTable          $localeTable,
        TranslationTable     $translationTable,
        TranslationBaseTable $translationBaseTable,
        TranslationFileTable $translationFileTable
    ) {
        $this->exportModule = $exportModule;

        parent::__construct($localeTable, $translationTable, $translationBaseTable, $translationFileTable);
    }

    /**
     * Get instance of export form.
     *
     * @return DebugExportForm
     */
    protected function getFormInstance()
    {
        return new DebugExportForm($this->translationFileTable->fetchAll(), $this->localeTable->fetchAll());
    }

    public function getFileName(int $fileId): string
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
    public function getTranslatedString(Translation $translation)
    {
        return sprintf(
            'DEBUG-ID_%d_%s_%s',
            $translation->getBaseId(),
            $this->getFileName($translation->getTranslationBase()->getFileId()),
            empty($translation->getTranslation()) ? $translation->getTranslationBase()->getOriginSource() : $translation->getTranslation()
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
        return new \ArrayIterator([ 'MasterFile.csv' ]);
    }

    public function getTranslations(string $locale, ?string $fileName): ResultSet_Translation
    {
        return $this->translationTable->fetchByLanguageAndFile($locale, null, false, null);
    }

    /**
     * Action "index".
     *
     * @return mixed
     */
    public function indexAction()
    {
        $view = parent::indexAction();
        $form = $view->getVariable('form');

//         $request = $this->getRequest();

//         if ($request->isPost()) {
//             $form->setData($request->getPost()->toArray());

//             // Validate form
//             if ($form->isValid()) {
// var_dump($form->getData());
// exit;
//                 parent::performExport($data);
//             }
//         }


        $debugView = new ViewModel([
            'form' => $form,
        ]);

        $debugView->setTemplate('debug');

        // See "template_map" in "module.config.php"
        $view->setTemplate('export')
            ->addChild($debugView, 'debugView');

        return $view;
    }
}

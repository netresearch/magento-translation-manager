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
    protected function getFormInstance(): DebugExportForm
    {
        return new DebugExportForm($this->translationFileTable->fetchAll(), $this->localeTable->fetchAll());
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

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost()->toArray());

            // Validate form
            if ($form->isValid()) {
var_dump($form->getData());
exit;
                parent::performExport($data);
            }
        }


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

<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Application\Form\SupportedLocaleForm;
use \Application\Model\SupportedLocale;
use \Application\Model\SupportedLocaleTable;

class LocaleController extends AbstractActionController
{
    /**
     * @var SupportedLocaleTable
     */
    private $_supportedLocale;

    public function __construct(SupportedLocaleTable $table)
    {
        $this->_supportedLocale = $table;
    }

    /**
     * Action "index".
     *
     * @return mixed
     */
    public function indexAction()
    {
        return [
            'locales' => $this->_supportedLocale->fetchAll(),
        ];
    }

    /**
     * Action "add".
     *
     * @return mixed
     */
    public function addAction()
    {
        $form = new SupportedLocaleForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $locale = new SupportedLocale();

            $form->setInputFilter($locale->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $locale->exchangeArray($form->getData());
                $this->_supportedLocale->saveSupportedLocale($locale);

                // Redirect to list of locales
                return $this->redirect()->toRoute('locale');
            }
        }

        return [
            'form' => $form,
        ];
    }

    /**
     * Action "edit".
     *
     * @return mixed
     */
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('locale', [
                'action' => 'add'
            ]);
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $locale = $this->_supportedLocale->getSupportedLocale($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('locale', [ 'action' => 'index' ]);
        }

        $form  = new SupportedLocaleForm();
        $form->bind($locale);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($locale->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->_supportedLocale->saveSupportedLocale($locale);

                // Redirect to list of locales
                return $this->redirect()->toRoute('locale');
            }
        }

        return [
            'id'   => $id,
            'form' => $form,
        ];
    }

    /**
     * Action "delete".
     *
     * @return mixed
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('locale');
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->_supportedLocale->deleteSupportedLocale($id);
            }

            // Redirect to list of locales
            return $this->redirect()->toRoute('locale');
        }

        return [
            'id'     => $id,
            'locale' => $this->_supportedLocale->getSupportedLocale($id)
        ];
    }
}

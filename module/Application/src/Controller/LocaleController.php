<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Application\Form\LocaleForm;
use \Application\Model\Locale;
use \Application\Model\LocaleTable;

class LocaleController extends AbstractActionController implements ControllerInterface
{
    /**
     * @var LocaleTable
     */
    private $_supportedLocale;

    public function __construct(LocaleTable $table)
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
        $form = new LocaleForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $locale = new Locale();

            $form->setInputFilter($locale->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $locale->exchangeArray($form->getData());
                $this->_supportedLocale->saveLocale($locale);

                $this->flashMessenger()->addSuccessMessage('Locale added');

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

        // Get the locale with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $locale = $this->_supportedLocale->getLocale($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('locale', [ 'action' => 'index' ]);
        }

        $form  = new LocaleForm();
        $form->bind($locale);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setInputFilter($locale->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->_supportedLocale->saveLocale($locale);

                $this->flashMessenger()->addSuccessMessage('Locale updated');

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

            if ($del === 'Yes') {
                $id = (int) $request->getPost('id');
                $this->_supportedLocale->deleteLocale($id);

                $this->flashMessenger()->addSuccessMessage('Locale deleted');
            }

            // Redirect to list of locales
            return $this->redirect()->toRoute('locale');
        }

        return [
            'id'     => $id,
            'locale' => $this->_supportedLocale->getLocale($id)
        ];
    }
}

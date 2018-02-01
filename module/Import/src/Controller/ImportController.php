<?php
namespace Import\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Import\Form\ImportForm;
// use \Application\Model\SupportedLocale;
use \Application\Model\SupportedLocaleTable;

class ImportController extends AbstractActionController
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
        $form    = new ImportForm($this->_supportedLocale->fetchAll());
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
                $data = $form->getData();

var_dump($data);
exit;
            }

//                 // Redirect to list of locales
//                 return $this->redirect()->toRoute('locale');
//             }
        }

        return [
            'form'             => $form,
            'supportedLocales' => $this->_supportedLocale->fetchAll(),
        ];
    }
}

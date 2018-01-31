<?php
namespace Import\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Import\Form\ImportForm;
use \Application\Model\SupportedLocale;
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
        return [
            'supportedLocales' => $this->_supportedLocale->fetchAll(),
        ];
    }
}

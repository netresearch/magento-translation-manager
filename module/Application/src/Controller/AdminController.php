<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController implements ControllerInterface
{
    use Traits\ControllerConstructor;

    /**
     * Admin dashboard
     *
     * @return mixed
     */
    public function indexAction()
    {
    }
}

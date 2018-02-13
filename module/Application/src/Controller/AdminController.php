<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;

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

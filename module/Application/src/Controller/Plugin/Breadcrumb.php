<?php
namespace Application\Controller\Plugin;

use \Zend\Mvc\Controller\Plugin\AbstractPlugin;
use \Zend\Navigation\Navigation;
use \Zend\Navigation\Page\AbstractPage;

class Breadcrumb extends AbstractPlugin
{
    /**
     * @var Navigation
     */
    private $navigation;

    /**
     * Constructor.
     *
     * @param Navigation $navigation Navigation instance
     */
    public function __construct(Navigation $navigation)
    {
        $this->navigation = $navigation;
    }

    /**
     * @param string                              $route Name of route to look
     * @param Page\AbstractPage|array|Traversable $page  Page to add
     */
    public function addBreadcrumb(string $route, $page)
    {
        /** @var AbstractPage $existingPage */
        $existingPage = $this->navigation->findOneByRoute($route);

        if ($existingPage) {
            $existingPage->addPage($page);
            return true;
        }

        return false;
    }
}

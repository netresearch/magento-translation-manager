<?php
namespace Export;

use \Zend\ModuleManager\Feature\ConfigProviderInterface;
use \Zend\ModuleManager\Feature\ControllerProviderInterface;
use \Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, ControllerProviderInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig(): array
    {
        return [
            'factories' => [
                Controller\ExportController::class => function (ServiceManager $sm) {
                    return new Controller\ExportController(
                        $sm->get(\Application\Model\LocaleTable::class),
                        $sm->get(\Application\Model\TranslationTable::class),
                        $sm->get(\Application\Model\TranslationFileTable::class)
                    );
                },
            ],
        ];
    }
}

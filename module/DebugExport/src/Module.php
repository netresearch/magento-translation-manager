<?php
namespace DebugExport;

use \Zend\ModuleManager\Feature\ConfigProviderInterface;
use \Zend\ModuleManager\Feature\ControllerProviderInterface;
use \Zend\ServiceManager\ServiceManager;
use \Zend\EventManager\EventInterface as Event;
use \Zend\ModuleManager\ModuleManager;

class Module implements ConfigProviderInterface, ControllerProviderInterface
{
    private $exportModule;

    public function init(ModuleManager $moduleManager)
    {
        // Remember to keep the init() method as lightweight as possible
        $events = $moduleManager->getEventManager();
        $events->attach('loadModules.post', array($this, 'modulesLoaded'));
    }

    public function modulesLoaded(Event $e)
    {
        /** @var \Zend\ModuleManager\ModuleManager $moduleManager */
        $moduleManager      = $e->getTarget();
        $this->exportModule = $moduleManager->getModule('Export');

        // To get the configuration from another module named 'FooModule'
//         $config = $moduleManager->getModule('FooModule')->getConfig();
    }
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
                Controller\DebugExportController::class => function (ServiceManager $sm) {
                    return new Controller\DebugExportController(
                        $this->exportModule,
                        $sm->get(\Application\Model\LocaleTable::class),
                        $sm->get(\Application\Model\TranslationTable::class),
                        $sm->get(\Application\Model\TranslationBaseTable::class),
                        $sm->get(\Application\Model\TranslationFileTable::class)
                    );
                },
            ],
        ];
    }
}

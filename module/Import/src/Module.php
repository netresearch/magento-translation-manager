<?php
namespace Import;

use \Zend\ModuleManager\Feature\ConfigProviderInterface;
use \Zend\ModuleManager\Feature\ControllerProviderInterface;
use \Zend\ModuleManager\Feature\ServiceProviderInterface;
use \Zend\Db\Adapter\AdapterInterface;
use \Zend\Db\TableGateway\TableGateway;
use \Zend\ServiceManager\ServiceManager;

class Module implements ConfigProviderInterface, ControllerProviderInterface, ServiceProviderInterface
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
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig(): array
    {
        return [
            'factories' => [
//                 Model\LocaleTable::class => function (ServiceManager $sm) {
//                     $tableGateway = $sm->get('Model\LocaleGateway');
//                     return new Model\LocaleTable($tableGateway);
//                 },

//                 'Model\LocaleGateway' => function (ServiceManager $sm) {
//                     $dbAdapter = $sm->get(AdapterInterface::class);

//                     $resultSetPrototype = new ResultSet\Locale();
//                     $resultSetPrototype->setArrayObjectPrototype(new Model\Locale());
//                     $resultSetPrototype->buffer();

//                     return new TableGateway('locale', $dbAdapter, null, $resultSetPrototype);
//                 },
            ],
        ];
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
                Controller\ImportController::class => function (ServiceManager $sm) {
                    return new Controller\ImportController(
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

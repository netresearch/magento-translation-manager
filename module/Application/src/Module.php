<?php
namespace Application;

use \Zend\ModuleManager\Feature\BootstrapListenerInterface;
use \Zend\ModuleManager\Feature\ConfigProviderInterface;
use \Zend\ModuleManager\Feature\ControllerProviderInterface;
use \Zend\ModuleManager\Feature\ServiceProviderInterface;
use \Zend\Db\Adapter\AdapterInterface;
use \Zend\Db\TableGateway\TableGateway;
use \Zend\ServiceManager\ServiceManager;
use \Zend\Mvc\ModuleRouteListener;
use \Zend\Mvc\MvcEvent;
use \Zend\EventManager\EventInterface;
use \Zend\ModuleManager\ModuleManager;
use \Zend\ModuleManager\ModuleEvent;

class Module implements BootstrapListenerInterface, ConfigProviderInterface, ControllerProviderInterface, ServiceProviderInterface
{
    /**
     * Available locales in the application
     */
    const LOCALE_AVAILABLE = 'de_DE,en_US';

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->setLocaleByAcceptedLang($e);
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
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                // Table "locale"
                Model\LocaleTable::class => function (ServiceManager $sm) {
                    return new Model\LocaleTable(
                        $sm->get('Model\LocaleGateway')
                    );
                },

                'Model\LocaleGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);

                    $resultSetPrototype = new ResultSet\Locale();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Locale());
                    $resultSetPrototype->buffer();

                    return new TableGateway('locale', $dbAdapter, null, $resultSetPrototype);
                },

                // Table "translation"
                Model\TranslationTable::class => function (ServiceManager $sm) {
                    return new Model\TranslationTable(
                        $sm->get('Model\TranslationGateway')
                    );
                },

                'Model\TranslationGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);

                    $resultSetPrototype = new ResultSet\Translation();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Translation());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translation', $dbAdapter, null, $resultSetPrototype);
                },

                // Table "translationBase"
                Model\TranslationBaseTable::class => function (ServiceManager $sm) {
                    return new Model\TranslationBaseTable(
                        $sm->get('Model\TranslationBaseGateway')
                    );
                },

                'Model\TranslationBaseGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);

                    $resultSetPrototype = new ResultSet\TranslationBase();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\TranslationBase());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translationBase', $dbAdapter, null, $resultSetPrototype);
                },

                // Table "translationFile"
                Model\TranslationFileTable::class => function (ServiceManager $sm) {
                    return new Model\TranslationFileTable(
                        $sm->get('Model\TranslationFileGateway')
                    );
                },

                'Model\TranslationFileGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);

                    $resultSetPrototype = new ResultSet\TranslationFile();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\TranslationFile());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translationFile', $dbAdapter, null, $resultSetPrototype);
                },

                // Table "suggestion"
                Model\SuggestionTable::class => function (ServiceManager $sm) {
                    return new Model\SuggestionTable(
                        $sm->get('Model\SuggestionGateway')
                    );
                },

                'Model\SuggestionGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get(AdapterInterface::class);

                    $resultSetPrototype = new ResultSet\Suggestion();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Suggestion());
                    $resultSetPrototype->buffer();

                    return new TableGateway('suggestion', $dbAdapter, null, $resultSetPrototype);
                },
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
                Controller\IndexController::class => function (ServiceManager $sm) {
                    return new Controller\IndexController(
                        $sm->get(\Application\Model\LocaleTable::class),
                        $sm->get(\Application\Model\TranslationTable::class),
                        $sm->get(\Application\Model\TranslationBaseTable::class),
                        $sm->get(\Application\Model\TranslationFileTable::class),
                        $sm->get(\Application\Model\SuggestionTable::class)
                    );
                },

                Controller\AdminController::class => function (ServiceManager $sm) {
                    return new Controller\AdminController(
                        $sm->get(\Application\Model\LocaleTable::class),
                        $sm->get(\Application\Model\TranslationTable::class),
                        $sm->get(\Application\Model\TranslationBaseTable::class),
                        $sm->get(\Application\Model\TranslationFileTable::class),
                        $sm->get(\Application\Model\SuggestionTable::class)
                    );
                },

                Controller\AjaxController::class => function (ServiceManager $sm) {
                    return new Controller\AjaxController(
                        $sm->get(\Application\Model\LocaleTable::class),
                        $sm->get(\Application\Model\TranslationTable::class),
                        $sm->get(\Application\Model\TranslationBaseTable::class),
                        $sm->get(\Application\Model\TranslationFileTable::class),
                        $sm->get(\Application\Model\SuggestionTable::class)
                    );
                },

                Controller\LocaleController::class => function (ServiceManager $sm) {
                    return new Controller\LocaleController(
                        $sm->get(Model\LocaleTable::class)
                    );
                },
            ],
        ];
    }

    /**
     * Define locale by HTTP Header Accept-Language
     *
     * @param EventInterface $e
     *
     * @return void
     */
    private function setLocaleByAcceptedLang(EventInterface $e): void
    {
        /** @var \Zend\Http\Request $request */
        $request = $e->getRequest();
        $headers = $request->getHeaders();

        if ($headers->has('Accept-Language')) {
            $availableLocales = explode(',', self::LOCALE_AVAILABLE);
            $locales = $headers->get('Accept-Language')->getPrioritized();

            foreach ($locales as $locale) {
                $localeString = $locale->getLanguage();
                if (false === strpos($localeString, '-')) {
                    // de    => de_DE
                    $localeString = $localeString . '_' . strtoupper($localeString);
                } else {
                    // en-US => en_US
                    $localeString = str_replace('-', '_', $localeString);
                }

                if (in_array($localeString, $availableLocales)) {
                    $e->getApplication()
                        ->getServiceManager()
                        ->get('MvcTranslator')
                        ->setLocale($localeString);
                    return;
                }
            }
        }
    }
}

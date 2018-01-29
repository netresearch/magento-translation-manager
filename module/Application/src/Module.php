<?php
namespace Application;

use \Zend\Mvc\ModuleRouteListener;
use \Zend\Mvc\MvcEvent;
use \Zend\ServiceManager\ServiceManager;
use \Zend\Db\TableGateway\TableGateway;
use \Application\ResultSet;
use \Application\Resource;
use \Application\Model;

class Module
{
    /**
     * available locales in the application
     */
    const LOCALE_AVAILABLE = 'de_DE,en_US';


    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->setLocaleByAcceptedLang($e);
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                // supported_locale table
                'Application\Resource\SupportedLocale' => function (ServiceManager $sm) {
                    $tableGateway = $sm->get('SupportedLocaleGateway');
                    return new Resource\SupportedLocale($tableGateway);
                },

                'SupportedLocaleGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new ResultSet\SupportedLocale();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\SupportedLocale());
                    $resultSetPrototype->buffer();

                    return new TableGateway('supported_locale', $dbAdapter, null, $resultSetPrototype);
                },

                // translation table
                'Application\Resource\Translation' => function (ServiceManager $sm) {
                    $tableGateway = $sm->get('TranslationTableGateway');
                    return new Resource\Translation($tableGateway);
                },

                'TranslationTableGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new ResultSet\Translation();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Translation());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translation', $dbAdapter, null, $resultSetPrototype);
                },

                // translation_base table
                'Application\Resource\TranslationBase' => function (ServiceManager $sm) {
                    $tableGateway  = $sm->get('TranslationBaseTableGateway');
                    return new Resource\TranslationBase($tableGateway);
                },

                'TranslationBaseTableGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new ResultSet\TranslationBase();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\TranslationBase());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translation_base', $dbAdapter, null, $resultSetPrototype);
                },

                // translation_file table
                'Application\Resource\TranslationFile' => function (ServiceManager $sm) {
                    $tableGateway = $sm->get('TranslationFileTableGateway');
                    return new Resource\TranslationFile($tableGateway);
                },

                'TranslationFileTableGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new ResultSet\TranslationFile();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\TranslationFile());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translation_file', $dbAdapter, null, $resultSetPrototype);
                },

                // suggestion table
                'Application\Resource\Suggestion' => function (ServiceManager $sm) {
                    $tableGateway = $sm->get('SuggestionGateway');
                    return new Resource\Suggestion($tableGateway);
                },

                'SuggestionGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new ResultSet\Suggestion();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Suggestion());
                    $resultSetPrototype->buffer();

                    return new TableGateway('suggestion', $dbAdapter, null, $resultSetPrototype);
                },
             ),
        );
    }

    /**
     * define locale by HTTP Header Accept-Language
     *
     * @param MvcEvent $e
     */
    protected function setLocaleByAcceptedLang($e)
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

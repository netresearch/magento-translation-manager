<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use \Zend\Mvc\ModuleRouteListener;
use \Zend\Mvc\MvcEvent;
use \Zend\ServiceManager\ServiceManager;
use \Zend\Db\ResultSet\ResultSet;
use \Zend\Db\TableGateway\TableGateway;

use \Application\Model\Translation;
use \Application\Model\TranslationBase;
use \Application\Model\TranslationFile;
use \Application\Model\SupportedLocale;
use \Application\Model\Suggestion;


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
                    return new \Application\Resource\SupportedLocale($tableGateway);
                },

                'SupportedLocaleGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new \Application\ResultSet\SupportedLocale();
                    $resultSetPrototype->setArrayObjectPrototype(new SupportedLocale());
                    $resultSetPrototype->buffer();

                    return new TableGateway('supported_locale', $dbAdapter, null, $resultSetPrototype);
                },

                // translation table
                'Application\Resource\Translation' => function (ServiceManager $sm) {
                    $tableGateway = $sm->get('TranslationTableGateway');
                    return new \Application\Resource\Translation($tableGateway);
                },

                'TranslationTableGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new \Application\ResultSet\Translation();
                    $resultSetPrototype->setArrayObjectPrototype(new Translation());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translation', $dbAdapter, null, $resultSetPrototype);
                },

                // translation_base table
                'Application\Resource\TranslationBase' => function (ServiceManager $sm) {
                    $tableGateway  = $sm->get('TranslationBaseTableGateway');
                    return new \Application\Resource\TranslationBase($tableGateway);
                },

                'TranslationBaseTableGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new \Application\ResultSet\TranslationBase();
                    $resultSetPrototype->setArrayObjectPrototype(new TranslationBase());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translation_base', $dbAdapter, null, $resultSetPrototype);
                },

                // translation_file table
                'Application\Resource\TranslationFile' => function (ServiceManager $sm) {
                    $tableGateway = $sm->get('TranslationFileTableGateway');
                    return new \Application\Resource\TranslationFile($tableGateway);
                },

                'TranslationFileTableGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new \Application\ResultSet\TranslationFile();
                    $resultSetPrototype->setArrayObjectPrototype(new TranslationFile());
                    $resultSetPrototype->buffer();

                    return new TableGateway('translation_file', $dbAdapter, null, $resultSetPrototype);
                },

                // suggestion table
                'Application\Resource\Suggestion' => function (ServiceManager $sm) {
                    $tableGateway = $sm->get('SuggestionGateway');
                    return new \Application\Resource\Suggestion($tableGateway);
                },

                'SuggestionGateway' => function (ServiceManager $sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

                    $resultSetPrototype = new \Application\ResultSet\Suggestion();
                    $resultSetPrototype->setArrayObjectPrototype(new Suggestion());
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

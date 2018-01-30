<?php

namespace Application\Factory;

use \Interop\Container\ContainerInterface;
use \Application\Controller\LocaleController;

class LocaleControllerFactory
{
    public function __invoke(ContainerInterface $container): LocaleController
    {
        return new LocaleController(
            $container->get('Application\Resource\SupportedLocale'),
            $container->get('Application\Resource\Translation'),
            $container->get('Application\Resource\TranslationBase'),
            $container->get('Application\Resource\TranslationFile'),
            $container->get('Application\Resource\Suggestion')
        );
    }
}

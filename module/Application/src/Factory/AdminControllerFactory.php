<?php

namespace Application\Factory;

use \Interop\Container\ContainerInterface;

class AdminControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new \Application\ControllerAdminController(
            $container->get('Application\Resource\SupportedLocale'),
            $container->get('Application\Resource\Translation'),
            $container->get('Application\Resource\TranslationBase'),
            $container->get('Application\Resource\TranslationFile'),
            $container->get('Application\Resource\Suggestion')
        );
    }
}
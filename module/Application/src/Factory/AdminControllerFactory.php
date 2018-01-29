<?php

namespace Application\Factory;

use \Interop\Container\ContainerInterface;
use \Application\Controller\AdminController;

class AdminControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AdminController(
            $container->get('Application\Resource\SupportedLocale'),
            $container->get('Application\Resource\Translation'),
            $container->get('Application\Resource\TranslationBase'),
            $container->get('Application\Resource\TranslationFile'),
            $container->get('Application\Resource\Suggestion')
        );
    }
}

<?php

namespace Application\Factory;

use \Interop\Container\ContainerInterface;
use \Application\Controller\IndexController;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container): IndexController
    {
        return new IndexController(
            $container->get('Application\Resource\SupportedLocale'),
            $container->get('Application\Resource\Translation'),
            $container->get('Application\Resource\TranslationBase'),
            $container->get('Application\Resource\TranslationFile'),
            $container->get('Application\Resource\Suggestion')
        );
    }
}

<?php

namespace Application\Factory;

use \Interop\Container\ContainerInterface;

class AjaxControllerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new \Application\Controller\AjaxController(
            $container->get('Application\Resource\SupportedLocale'),
            $container->get('Application\Resource\Translation'),
            $container->get('Application\Resource\TranslationBase'),
            $container->get('Application\Resource\TranslationFile'),
            $container->get('Application\Resource\Suggestion')
        );
    }
}
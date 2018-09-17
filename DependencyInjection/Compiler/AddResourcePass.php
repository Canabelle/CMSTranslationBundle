<?php

namespace Canabelle\CMSTranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AddResourcePass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $dbLoader = $container->get('translation.loader.db');

        $translator = $container->findDefinition('translator.default');
        if (null !== $translator) {
            foreach ($dbLoader->getAvailableDomains() as $domain) {
                foreach ($dbLoader->getAvailableLocalesForDomain($domain) as $locale) {
                    $translator->addMethodCall(
                        'addResource',
                        array(
                            'db',
                            new Reference('canabelle_cms_translation.resource'),
                            $locale,
                            $domain,
                        )
                    );
                }
            }
        }
    }
}

<?php

namespace Canabelle\CMSTranslationBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Canabelle\CMSTranslationBundle\DependencyInjection\Compiler\TemplatingPass,
    Canabelle\CMSTranslationBundle\DependencyInjection\Compiler\AddResourcePass;

class CanabelleCMSTranslationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TemplatingPass());
        $container->addCompilerPass(new AddResourcePass(), PassConfig::TYPE_AFTER_REMOVING);
    }
}

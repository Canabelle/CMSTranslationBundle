<?php

namespace Canabelle\CMSTranslationBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\OptionsResolver\Options,
    Symfony\Component\HttpFoundation\RequestStack;

/**
 * Translated entity
 */
class TranslatedEntityType extends AbstractType
{
    private $request;

    public function setRequest(RequestStack $requestStack = null)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_path' => 'translations',
            'translation_property' => null,
            'property' => function(Options $options) {
                if (null === $this->request) {
                    throw new \Exception('Error while getting request');
                }

                return $options['translation_path'] .'['. $this->request->getLocale() .'].'. $options['translation_property'];
            },
        ));
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'canabelle_cms_translatedEntity';
    }
}

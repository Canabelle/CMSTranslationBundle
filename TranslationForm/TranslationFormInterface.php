<?php

namespace Canabelle\CMSTranslationBundle\TranslationForm;

/**
 * @author David ALLIX
 */
interface TranslationFormInterface
{
    /**
     *
     */
    public function getFieldsOptions($class, $options);

    /**
     *
     */
    public function guessMissingFieldOptions($guesser, $class, $property, $options);
}

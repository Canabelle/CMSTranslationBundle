<?php

namespace Canabelle\CMSTranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LanguageTranslation
 *
 * @ORM\Table(name="language_translation")
 * @ORM\Entity(repositoryClass="Canabelle\CMSTranslationBundle\Entity\LanguageTranslationRepository")
 */
class LanguageTranslation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="translation", type="text", nullable=true)
     */
    private $translation;

    /**
     * @ORM\Column(name="language", type="string", length=20)
     */
    private $language;

    /**
     * @ORM\ManyToOne(targetEntity="Canabelle\CMSTranslationBundle\Entity\LanguageToken", inversedBy="translations", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $languageToken;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set translation
     *
     * @param string $translation
     * @return LanguageTranslation
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * Get translation
     *
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return LanguageTranslation
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set languageToken
     *
     * @param \Canabelle\CMSTranslationBundle\Entity\LanguageToken $languageToken
     * @return LanguageTranslation
     */
    public function setLanguageToken(\Canabelle\CMSTranslationBundle\Entity\LanguageToken $languageToken = null)
    {
        $this->languageToken = $languageToken;

        return $this;
    }

    /**
     * Get languageToken
     *
     * @return \Canabelle\CMSTranslationBundle\Entity\LanguageToken
     */
    public function getLanguageToken()
    {
        return $this->languageToken;
    }

    public function __toString()
    {
        return $this->language." - ".$this->translation;
    }
}

<?php

namespace Canabelle\CMSTranslationBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * LanguageCatalogue
 *
 * @ORM\Table(name="language_catalogue")
 * @ORM\Entity()
 * @UniqueEntity("name")
 */
class LanguageCatalogue
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
     * @ORM\OneToMany(targetEntity="Canabelle\CMSTranslationBundle\Entity\LanguageToken", mappedBy="catalogue", cascade={"persist", "remove"})
     */
    private $tokens;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tokens = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return LanguageCatalogue
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add token
     *
     * @param \Canabelle\CMSTranslationBundle\Entity\LanguageToken $token
     * @return LanguageCatalogue
     */
    public function addToken(\Canabelle\CMSTranslationBundle\Entity\LanguageToken $token)
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * Remove token
     *
     * @param \Canabelle\CMSTranslationBundle\Entity\LanguageToken $token
     */
    public function removeToken(\Canabelle\CMSTranslationBundle\Entity\LanguageToken $token)
    {
        $this->tokens->removeElement($token);
    }

    /**
     * Get tokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    public function __toString()
    {
        return $this->name;
    }
}

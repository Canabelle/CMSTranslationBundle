<?php

namespace Canabelle\CMSTranslationBundle\Services;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Doctrine\ORM\EntityManager;

class DBLoader implements LoaderInterface
{
    private $translationRepository;
    private $catalogueRepository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->translationRepository = $entityManager->getRepository("CanabelleCMSTranslationBundle:LanguageTranslation");
        $this->catalogueRepository = $entityManager->getRepository("CanabelleCMSTranslationBundle:LanguageCatalogue");
    }

    /**
     * @param mixed $resource
     * @param string $locale
     * @param string $domain
     * @return MessageCatalogue
     * @throws TableNotFoundException
     */
    function load($resource, $locale, $domain = 'messages')
    {
        try {
            $cataloguesDB = $this->catalogueRepository->findAll();
        } catch (TableNotFoundException $e) {
            $cataloguesDB = [];
        }

        $catalogue = new MessageCatalogue($locale);
        foreach ($cataloguesDB as $ctlg) {
            $translations = $this->translationRepository->getTranslations($locale, $ctlg->getName());
            foreach ($translations as $token => $translation) {
                $catalogue->set($token, $translation, $ctlg->getName());
            }
        }

        return $catalogue;
    }

    /**
     * @return array
     * @throws TableNotFoundException
     */
    public function getAvailableDomains()
    {
        try {
            $catalogues = $this->catalogueRepository->findAll();
        } catch (TableNotFoundException $e) {
            $catalogues = [];
        }

        $domains = [];

        foreach ($catalogues as $cat) {
            $domains[] = $cat->getName();
        }

        return $domains;
    }

    public function getAvailableLocalesForDomain($domain)
    {
        return $this->translationRepository->getLocalesForDomain($domain);
    }
}
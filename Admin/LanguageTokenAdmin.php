<?php

namespace Canabelle\CMSTranslationBundle\Admin;

use Canabelle\CMSTranslationBundle\Entity\LanguageToken;
use Ok99\PrivateZoneCore\AdminBundle\Admin\Admin as BaseAdmin;
use Ok99\PrivateZoneCore\PageBundle\Entity\Site;
use Ok99\PrivateZoneCore\PageBundle\Entity\SitePool;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\SiteManagerInterface;

class LanguageTokenAdmin extends BaseAdmin
{
    /** @var SiteManagerInterface */
    protected $siteManager;

    /** @var SitePool */
    protected $sitePool;

    protected function configureRoutes(RouteCollection $collection)
    {
        if (!$this->isAdmin()) {
            $collection->remove('create');
            $collection->remove('delete');
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var Site $site */
        $site = $this->getSite();
        if (!$site) {
            $formMapper
                ->add('token', 'text', array('label' => 'Key'));
        } else {
            $formMapper
                ->add('token', 'text', array('label' => 'Key', 'data' => strtolower($site->getSlug()).'.'));
        }
        $formMapper
            ->add('catalogue', 'sonata_type_model_list', array())
            ->add('translations', 'sonata_type_collection', array(), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable'  => 'position',
                'admin_code' => 'canabelle.cms.admin.translation'
            ));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('token')
            ->add('catalogue')
            ->add('site', null, array(
                'show_filter' => false,
            ))
        ;
    }

    public function getExportFields()
    {
        $results = $this->getModelManager()->getExportFields($this->getClass());
        $results[] = 'export_translations';
        return $results;
    }


    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('token');

        if ($this->isAdmin()) {
            $listMapper
                ->addIdentifier('catalogue')
                ->addIdentifier('site');
        }

        $listMapper->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    // Fields to be shown on revisions
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('token', null, array('label' => 'Key'))
            ->add('catalogue', null, array('label' => 'Catalogue'))
        ;
    }

    /**
     * @param LanguageToken $object
     * @return void
     */
    public function prePersist($object)
    {
        $site = $this->sitePool->getCurrentSite($this->getRequest());

        foreach ($object->getTranslations() as $tr) {
            $tr->setLanguageToken($object);
        }
        $object->setSite($site);
        $this->clearCache();
    }

    /**
     * @param LanguageToken $object
     * @return void
     */
    public function preUpdate($object)
    {
        foreach ($object->getTranslations() as $tr) {
            $tr->setLanguageToken($object);
        }
        $this->clearCache();
    }

    public function postRemove($object)
    {
        $this->clearCache();
    }

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        if (!$this->hasRequest()) {
            return $instance;
        }

        if ($site = $this->getSite()) {
            $instance->setSite($site);
        }

        return $instance;
    }

    /**
     * @param null|LanguageToken $object
     * @return bool
     */
    public function isAdmin($object = null)
    {
        return $object ? $this->isGranted('ADMIN', $object) : $this->isGranted('ADMIN');
    }

    /**
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->isGranted('ROLE_SUPER_ADMIN');
    }

    /**
     * @return null|SiteInterface
     *
     * @throws \RuntimeException
     */
    public function getSite()
    {
        if (!$this->hasRequest()) {
            return null;
        }

        $siteId = null;

        if ($this->getRequest()->getMethod() == 'POST') {
            $values = $this->getRequest()->get($this->getUniqid());
            $siteId = isset($values['site']) ? $values['site'] : null;
        }

        $siteId = (null !== $siteId) ? $siteId : $this->getRequest()->get('siteId');

        if ($siteId) {
            /** @var SiteInterface $site */
            $site = $this->siteManager->findOneBy(array('id' => $siteId));

            if (!$site) {
                throw new \RuntimeException('Unable to find the site with id=' . $this->getRequest()->get('siteId'));
            }

            return $site;
        }

        return null;
    }

    /**
     * @param \Sonata\PageBundle\Model\SiteManagerInterface $siteManager
     */
    public function setSiteManager(SiteManagerInterface $siteManager)
    {
        $this->siteManager = $siteManager;
    }

    public function setSitePool(SitePool $sitePool)
    {
        $this->sitePool = $sitePool;
    }

    public function clearCache()
    {
        $container = $this->getConfigurationPool()->getContainer();
        $filesystem = $container->get('filesystem');
        $envs = ['prod', 'dev'];

        foreach ($envs as $env) {
            $cacheDir = $container->getParameter('kernel.cache_dir').'/../'.$env.'/translations';
            $filesystem->remove($cacheDir);
        }
    }
}

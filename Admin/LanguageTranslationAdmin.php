<?php

namespace Canabelle\CMSTranslationBundle\Admin;

use Ok99\PrivateZoneCore\AdminBundle\Admin\Admin as BaseAdmin;
use Ok99\PrivateZoneCore\PageBundle\Entity\LanguageVersion;
use Ok99\PrivateZoneCore\PageBundle\Entity\SitePool;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class LanguageTranslationAdmin extends BaseAdmin
{
    protected $sitePool;

    public function __construct($code, $class, $baseControllerName, SitePool $sitePool)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->sitePool = $sitePool;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $currentSite = $this->sitePool->getCurrentSite($this->getRequest());
        $locales = array();

        /** @var LanguageVersion $lv */
        foreach ($currentSite->getLanguageVersions() as $lv) {
            $locales[$lv->getLocale()] = $lv->getName();
        }

        $formMapper
            ->add('language', 'choice', array('label' => 'Jazyk', 'expanded' => false, 'choices' => $locales))
            ->add('translation', 'text', array('label' => 'Překlad'))
            ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('language')
        ;
    }

    public function getExportFields()
    {
        return array(
                'language',
                'translation',
        );
    }


    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('language')
            ->addIdentifier('translation')
            ->add('_action', 'actions', array(
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
            ->add('language', null, array('label' => 'Language'))
            ->add('translation', null, array('label' => 'Translation'))
        ;
    }
}
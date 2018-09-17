<?php

namespace Canabelle\CMSTranslationBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SystemLanguageTokenAdminController extends Controller
{
    /**
     * @param Request|null $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Runtime
     */
    public function listAction(Request $request = null)
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $sitePool = $this->get('ok99.privatezone.site.pool');
        $sites = $sitePool->getSites();

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render('CanabelleCMSTranslationBundle:SystemLanguageTokenAdmin:list.html.twig', array(
            'action' => 'list',
            'sites' => $sites,
            'datagrid' => $datagrid,
            'form' => $formView,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
        ));
    }
}

<?php

namespace whm\SmokeBundle\Controller;

use phmLabs\Base\Www\Uri;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use whm\Smoke\Config\Configuration;
use whm\Smoke\Scanner\Scanner;

class DefaultController extends Controller
{
    private function getForm()
    {
        return $this->createFormBuilder()
            ->add('url', 'url', array('label' => 'URL', 'mapped' => false))
            ->add('saveAndAdd', 'submit', array('label' => 'Analyze'))
            ->setAction($this->generateUrl('whm_smoke_analyze'))
            ->getForm();
    }

    public function indexAction()
    {
        $form = $this->getForm();
        return $this->render('whmSmokeBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

    public function analyzeAction(Request $request)
    {
        $form = $this->getForm();
        $form->handleRequest($request);
        $data = $request->get("form");
        if ($form->isValid()) {
            $results = $this->analyzeUrl($data["url"]);
            return $this->render('whmSmokeBundle:Default:result.html.twig', array('results' => $results, "url" => $data["url"]));
        }
        $form->get("url")->setData($data["url"]);
        return $this->redirect($this->generateUrl("whm_smoke_homepage"));
    }

    private function analyzeUrl($url)
    {
        $config = Configuration::getDefaultConfig(new Uri($url));
        $config->setContainerSize(20);
        $config->setParallelRequestCount(20);
        $scanner = new Scanner($config);
        return $scanner->scan();
    }
}

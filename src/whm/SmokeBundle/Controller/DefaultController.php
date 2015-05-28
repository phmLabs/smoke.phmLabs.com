<?php

namespace whm\SmokeBundle\Controller;

use phmLabs\Base\Www\Uri;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use whm\Smoke\Config\Configuration;
use whm\Smoke\Scanner\Scanner;
use whm\SmokeBundle\Entity\ResultSet;

class DefaultController extends Controller
{
    const NUM_URLS = 100;

    private function getForm()
    {
        return $this->createFormBuilder()
            ->add('url', 'url', array('label' => false, 'mapped' => false))
            ->setAction($this->generateUrl('whm_smoke_analyze'))
            ->getForm();
    }

    public function indexAction()
    {
        $recentResults = $this->getDoctrine()->getRepository('whmSmokeBundle:ResultSet')->findNewest(10);
        return $this->render('whmSmokeBundle:Default:index.html.twig', array('recentResults' => $recentResults, 'form' => $this->getForm()->createView()));
    }

    public function analyzeAction(Request $request)
    {
        $form = $this->getForm();
        $form->handleRequest($request);
        $data = $request->get("form");
        if ($form->isValid()) {
            $results = $this->analyzeUrl($data["url"], self::NUM_URLS);

            $resultSet = new ResultSet();
            $resultSet->setUrl($data["url"]);
            $resultSet->setNumUrls(self::NUM_URLS);
            $resultSet->setResults($results);
            $resultSet->setDate(new \DateTime("now"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($resultSet);
            $em->flush();

            return $this->redirect($this->generateUrl("whm_smoke_show", array("resultSet" => $resultSet->getId())));
        }
        $form->get("url")->setData($data["url"]);
        return $this->redirect($this->generateUrl("whm_smoke_homepage"));
    }

    public function showAction(ResultSet $resultSet)
    {
        $response = $this->render('whmSmokeBundle:Default:result.html.twig', array('num' => $resultSet->getNumUrls(), 'results' => $resultSet->getResults(), "url" => $resultSet->getUrl()));

        $response->setPublic();
        $response->setMaxAge(86400);

        return $response;
    }

    private function analyzeUrl($url, $size)
    {
        $config = Configuration::getDefaultConfig(new Uri($url));
        $config->setContainerSize($size);
        $config->setParallelRequestCount(20);
        $scanner = new Scanner($config);
        return $scanner->scan();
    }
}

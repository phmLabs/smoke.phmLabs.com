<?php

namespace whm\SmokeBundle\Controller;

use phmLabs\Base\Www\Uri;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use whm\Smoke\Config\Configuration;
use whm\Smoke\Scanner\Scanner;
use whm\SmokeBundle\Entity\ResultSet;

class DefaultController extends Controller
{
    const NUM_URLS = 100;

    private function getForm()
    {
        return $this->createFormBuilder(null, ['attr' => ['id' => 'url_form']])
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
        set_time_limit(120);

        $form = $this->getForm();
        $form->handleRequest($request);
        $data = $request->get("form");
        if ($form->isValid()) {

            $url = $data["url"];

            if( !Uri::isValid($url)) {
                throw $this->createNotFoundException('The url can not be analyzed');
            }

            if (strpos($url, 'http') === false) {
                $url = "http://" . $url;
            }

            $results = $this->analyzeUrl($url, self::NUM_URLS);

            $resultSet = new ResultSet();
            $resultSet->setUrl($url);
            $resultSet->setNumUrls(self::NUM_URLS);
            $resultSet->setResults($results);
            $resultSet->setDate(new \DateTime("now"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($resultSet);
            $em->flush();

            $response = new Response(json_encode(array('status' => "success", 'url' => $this->generateUrl("whm_smoke_show", array("resultSet" => $resultSet->getId())))));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        throw $this->createNotFoundException('The url can not be analyzed');
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

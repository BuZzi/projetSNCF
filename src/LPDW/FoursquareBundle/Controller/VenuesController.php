<?php

namespace LPDW\FoursquareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use LPDW\FoursquareBundle\Model\FoursquareApi;

class VenuesController extends Controller
{
    /**
     * @Route("/station/{name}/{latitude}/{longitude}/categories", name="lpdw_categories")
     * @Template()
     */
    public function categoriesAction($name, $latitude, $longitude)
    {
        $client_key = $this->container->getParameter('foursquare_client_key');
        $client_secret = $this->container->getParameter('foursquare_client_secret');

        $foursquare = new FoursquareAPI($client_key,$client_secret);

        return new Response('recu');
    }
}

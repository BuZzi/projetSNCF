<?php

namespace LPDW\FoursquareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use LPDW\FoursquareBundle\Model\FoursquareApi;

use LPDW\GoogleMapBundle\Model\Map;

class VenuesController extends Controller
{

    /**
     * @Route("/station/{name}/{latitude}/{longitude}/categories", name="lpdw_categories")
     * @Template("LPDWFoursquareBundle:Default:index.html.twig")
     */
    public function categoriesAction($name, $latitude, $longitude)
    {
        // Récupère la clé client et le code secret pour pouvoir requêter dans l'API Foursquare
        $_sClient_key = $this->container->getParameter('foursquare_client_key');
        $_sClient_secret = $this->container->getParameter('foursquare_client_secret');

        $_oFoursquare = new FoursquareAPI($_sClient_key,$_sClient_secret); // Appel de library FoursquareApi

        // effectue une requête à une ressource public
        $response = $_oFoursquare->GetPublic("venues/categories"); // On récupère ici la liste des catégories
        $listCategories = json_decode($response);

        //var_dump($listCategories);
        foreach($listCategories->response->categories as $categories){
            $_aCategories[] = $categories;
        }

        return array(
            'listCategories' => $_aCategories,
            'nameStation' => $name,
            'latitude' => $latitude,
            'longitude' => $longitude,
        );
    }

    /**
     * @Route("/station/{name}/{latitude}/{longitude}/{categorie}/", name="lpdw_venuesincategories")
     * @Template("LPDWFoursquareBundle:Default:map.html.twig")
     */
    public function venuesInCategorieAction($name, $latitude, $longitude, $categorie)
    {
        // Récupère la clé client et le code secret pour pouvoir requêter dans l'API Foursquare
        $_sClient_key = $this->container->getParameter('foursquare_client_key');
        $_sClient_secret = $this->container->getParameter('foursquare_client_secret');

        $_oFoursquare = new FoursquareAPI($_sClient_key,$_sClient_secret); // Appel de library FoursquareApi

        $params = array(
            "ll" => "$latitude,$longitude",
            "categoryId" => $categorie,
        ); // paramètres de notre position et de la catégorie que l'on recherche

        // effectue une requête à une ressource public
        $response = $_oFoursquare->GetPublic("venues/explore",$params); // On récupère ici la liste des lieux près de la gare
        $listVenues = json_decode($response);

        //var_dump($listVenues);

        $_oMap = new Map();
        $_oMap = $_oMap->buildMap();

        return array(
            'listVenues' => $listVenues,
            'googleMap' => $_oMap,
        );
    }
}

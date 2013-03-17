<?php

namespace LPDW\FoursquareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use LPDW\FoursquareBundle\Model\FoursquareApi;

use LPDW\GoogleMapBundle\Manager\GoogleMapManager;

class VenuesController extends Controller
{

    /**
     * @Route("/station/{name}/{latitude}/{longitude}/categories", name="lpdw_categories")
     * @Template("LPDWFoursquareBundle:Default:categories.html.twig")
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

        $_aParams = array(
            "ll" => "$latitude,$longitude",
            "categoryId" => $categorie,
        ); // paramètres de notre position et de la catégorie que l'on recherche

        // effectue une requête à une ressource public
        $response = $_oFoursquare->GetPublic("venues/explore",$_aParams); // On récupère ici la liste des lieux près de la gare
        $_oListVenues = json_decode($response);

        foreach($_oListVenues->response->groups as $_aListVenues){
            $_aListVenues = $_aListVenues->items;
        }

        // Construit la carte et affiche les lieux d'intérêt autour de notre position
        $_oMap = $this->get('lpdw_google_map.google_map_manager');
        $_oFinalMap = $_oMap->buildMap($latitude, $longitude);
        $_aVenues = array(
            'latitude' => $latitude,
            'longitude' => $longitude,
        );
        $_oMarker = $_oMap->createMarker($_aVenues);
        // Add your marker to the map
        $_oFinalMap->addMarker($_oMarker);

        foreach($_aListVenues as $_oVenues){
            //var_dump($_oVenues);
            $latitude = $_oVenues->venue->location->lat;
            $longitude = $_oVenues->venue->location->lng;
            $_aVenues = array(
                'latitude' => $latitude,
                'longitude' => $longitude,
            );

            $_oMarker = $_oMap->createMarker($_aVenues);
            // Add your marker to the map
            $_oFinalMap->addMarker($_oMarker);
        }

        var_dump($_oFinalMap);
        return array(
            //'listVenues' => $listVenues,
            'googleMap' => $_oFinalMap,
        );
    }
}

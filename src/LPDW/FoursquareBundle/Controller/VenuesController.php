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
        $_aVenues = array();
        //$_oMap = $this->get('lpdw_google_map.google_map_manager');

        // Récupère la clé client et le code secret pour pouvoir requêter dans l'API Foursquare
        $_sClient_key = $this->container->getParameter('foursquare_client_key');
        $_sClient_secret = $this->container->getParameter('foursquare_client_secret');
        $_oFoursquare = new FoursquareAPI($_sClient_key,$_sClient_secret); // Appel de library FoursquareApi

        $_aStationVenue = array(
            'name' => $name,
            'latitude' =>$latitude,
            'longitude' => $longitude,
        );

        $_aParams = array(
            "ll" => "$latitude,$longitude",
            "categoryId" => $categorie,
        ); // paramètres de notre position et de la catégorie que l'on recherche

        // effectue une requête à une ressource public
        $response = $_oFoursquare->GetPublic("venues/explore",$_aParams); // On récupère ici la liste des lieux près de la gare
        $_oListVenues = json_decode($response); // JSON en objet

        foreach($_oListVenues->response->groups as $_aListVenues){
            $_aListVenues = $_aListVenues->items;
        }

        foreach($_aListVenues as $_oVenues){
            //var_dump($_oVenues);
            $name = $_oVenues->venue->name;
            $latitude = $_oVenues->venue->location->lat;
            $longitude = $_oVenues->venue->location->lng;
            $address = $_oVenues->venue->location->address;
            $postalCode = $_oVenues->venue->location->postalCode;
            $city = $_oVenues->venue->location->city;
            $state = $_oVenues->venue->location->state;
            $country = $_oVenues->venue->location->country;
            $distance = $_oVenues->venue->location->distance;

            $_aVenues[] = array(
                'name' => $name,
                'latitude' =>$latitude,
                'longitude' => $longitude,
                'address' => $address,
                'postalCode' => $postalCode,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'distance' => $distance,
            );
        }


        /*
        foreach($_oListVenues->response->groups as $_aListVenues){
            $_aListVenues = $_aListVenues->items;
        }

        // Construit la carte et affiche les lieux d'intérêt autour de notre position
        $_oFinalMap = $_oMap->buildMap($latitude, $longitude);

        // Ajoute le marker de la gare où on se trouve
        $_aVenues['latitude'] = $latitude;
        $_aVenues['longitude'] = $longitude;
        $_oMarker = $_oMap->createMarker($_aVenues);// créé le marker
        $_oFinalMap->addMarker($_oMarker);// ajoute le marker à la carte

        // boucle sur les lieux autour de la garen créé un marker pour chaque et l'ajoute à la carte
        foreach($_aListVenues as $_oVenues){
            //var_dump($_oVenues);
            $latitude = $_oVenues->venue->location->lat;
            $longitude = $_oVenues->venue->location->lng;
            $_aVenues = array(
                'latitude' => $latitude,
                'longitude' => $longitude,
            );

            $_oMarker = $_oMap->createMarker($_aVenues); // Créé le marker
            //var_dump($_oMarker);
            $_oFinalMap->addMarker($_oMarker); // ajoute le marker à la carte
            //var_dump($_oFinalMap);
        }*/

        return array(
            'listVenues' => $_aVenues,
            'stationVenue' => $_aStationVenue,
            //'googleMap' => $_oFinalMap,
        );
    }
}

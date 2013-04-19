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
        $_sClient_key    = $this->container->getParameter('foursquare_client_key');
        $_sClient_secret = $this->container->getParameter('foursquare_client_secret');

        $_oFoursquare = new FoursquareAPI($_sClient_key,$_sClient_secret); // Appel de library FoursquareApi

        // effectue une requête à une ressource public
        $response = $_oFoursquare->GetPublic("venues/categories"); // On récupère ici la liste des catégories
        $listCategories = json_decode($response);

        $_aNeededCategories = array('Culture et loisirs', 'Manger', 'Extérieur et loisirs', 'Boutiques et services', 'Vie nocturne', 'Établissement universitaire');
        $_aCategories = array();

        //var_dump($listCategories);
        foreach($listCategories->response->categories as $categorie){
            // only get needed categories
            if(in_array($categorie->name, $_aNeededCategories, false)){
                $_aCategories[] = $categorie;
            }
        }

        if(empty($_aCategories)){
            $_aCategories = null;
        }

        $_oTwitter = $this->get('endroid.twitter');
        $_sName = urlencode($name);
        $_aParameters = array(
            'q' => '#appWhere '.$_sName,
        );

        // use the generic query method
        $_oResponse = $_oTwitter->query('search', 'GET', 'json', $_aParameters);
        $_oDatas = json_decode($_oResponse->getContent());
        $_aFinalTweets = array();

        if(!empty($_oDatas->results)){
            foreach($_oDatas->results as $_oTweet){

                $_oToday = new \DateTime('r');
                $_oTweetDate = new \DateTime($_oTweet->created_at);

                $_oDateDiff = date_diff($_oTweetDate, $_oToday);
                
                if($_oDateDiff->format('%d') == '0')
                    $_aFinalTweets[] = $_oTweet;
            }
        }

        return array(
            'listCategories' => $_aCategories,
            'nameStation' => $name,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'tweets' => $_aFinalTweets,
        );
    }

    /**
     * @Route("/station/{name}/{latitude}/{longitude}/{categorie}/", name="lpdw_venuesincategories")
     * @Template("LPDWFoursquareBundle:Default:map.html.twig")
     */
    public function venuesInCategorieAction($name, $latitude, $longitude, $categorie)
    {
        $_aVenues = array();
        $_oMap    = $this->get('lpdw_google_map.google_map_manager');

        // Récupère la clé client et le code secret pour pouvoir requêter dans l'API Foursquare
        $_sClient_key    = $this->container->getParameter('foursquare_client_key');
        $_sClient_secret = $this->container->getParameter('foursquare_client_secret');

        $_oFoursquare = new FoursquareAPI($_sClient_key,$_sClient_secret); // Appel de library FoursquareApi

        $_sStationName = $name;

        $_aStationVenue = array(
            'name' => 'Gare '.$_sStationName,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'address' => '',
            'postalCode' => '',
            'city' => '',
            'state' => '',
            'distance' => '0',
        );

        $_aParams = array(
            "ll" => "$latitude,$longitude",
            "categoryId" => $categorie,
            "radius" => 1000,
        ); // paramètres de notre position et de la catégorie que l'on recherche

        // effectue une requête à une ressource public
        $response = $_oFoursquare->GetPublic("venues/explore",$_aParams); // On récupère ici la liste des lieux près de la gare
        $_oListVenues = json_decode($response); // JSON en objet

        foreach($_oListVenues->response->groups as $_aListVenues){
            $_aListVenues = $_aListVenues->items;
        }

        // Construit la carte et affiche les lieux d'intérêt autour de notre position
        $_oFinalMap = $_oMap->buildMap($latitude, $longitude);

        // Ajoute le marker de la gare où on se trouve
        $_oMarker = $_oMap->createMarker($_aStationVenue, true);// Créé le marker de la gare où on se trouve
        $_oInfoWindow = $_oMap->createInfoWindow($_aStationVenue, $_sStationName); // créé l'infoWindow

        $_oMarker->setInfoWindow($_oInfoWindow); // ajoute l'infoWindow au marker
        $_oFinalMap->addMarker($_oMarker);// Ajoute le marker à la carte

        // Boucle sur les lieux autour de la gare, créé un marker pour chaque et l'ajoute à la carte
        foreach($_aListVenues as $_oVenues){

            if(isset($_oVenues->venue->name)){
                $name = $_oVenues->venue->name;
                $_aVenues['name'] = $name;
            } else {
                $_aVenues['name'] = '';
            }

            if(isset($_oVenues->venue->location->lat)){
                $latitude = $_oVenues->venue->location->lat;
                $_aVenues['latitude'] = $latitude;
            } else {
                $_aVenues['latitude'] = '';
            }

            if(isset($_oVenues->venue->location->lng)){
                $longitude = $_oVenues->venue->location->lng;
                $_aVenues['longitude'] = $longitude;
            } else {
                $_aVenues['longitude'] = '';
            }

            if(isset($_oVenues->venue->location->address)){
                $address = $_oVenues->venue->location->address;
                $_aVenues['address'] = $address;
            } else {
                $_aVenues['address'] = '';
            }

            if(isset($_oVenues->venue->location->postalCode)){
                $postalCode = $_oVenues->venue->location->postalCode;
                $_aVenues['postalCode'] = $postalCode;
            } else {
                $_aVenues['postalCode'] = '';
            }

            if(isset($_oVenues->venue->location->city)){
                $city = $_oVenues->venue->location->city;
                $_aVenues['city'] = $city;
            } else {
                $_aVenues['city'] = '';
            }

            if(isset($_oVenues->venue->location->state)){
                $state = $_oVenues->venue->location->state;
                $_aVenues['state'] = $state;
            } else {
                $_aVenues['state'] = '';
            }

            if(isset($_oVenues->venue->location->distance)){
                $distance = $_oVenues->venue->location->distance;
                $_aVenues['distance'] = $distance;
            } else {
                $_aVenues['distance'] = '';
            }

            $_oMarker = $_oMap->createMarker($_aVenues); // Créé le marker
            $_oInfoWindow = $_oMap->createInfoWindow($_aVenues, $_sStationName); // créé l'infoWindow

            $_oMarker->setInfoWindow($_oInfoWindow); // ajoute l'infoWindow au marker
            $_oFinalMap->addMarker($_oMarker); // ajoute le marker à la carte

            //var_dump($_oFinalMap);
        }

        return array(
            'listVenues' => $_aVenues,
            'stationVenue' => $_aStationVenue,
            'googleMap' => $_oFinalMap,
        );
    }
}

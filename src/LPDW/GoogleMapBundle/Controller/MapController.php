<?php

namespace LPDW\GoogleMapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Ivory\GoogleMapBundle\IvoryGoogleMapBundle,
    Ivory\GoogleMapBundle\Model\MapTypeId;

class MapController extends Controller
{
    /**
     * @Route("/map")
     * @Template("LPDWGoogleMapBundle:Map:index.html.twig")
     */
    public function mapAction()
    {
        // Requests the ivory google map service
        $map = $this->get('ivory_google_map.map');

        // Configure your map options
        $map->setPrefixJavascriptVariable('map_');
        $map->setHtmlContainerId('map_canvas');

        $map->setAsync(false);

        $map->setAutoZoom(false);

        $map->setCenter(0, 0, true);
        $map->setMapOption('zoom', 3);

        $map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
        $map->setMapOption('mapTypeId', 'roadmap');

        $map->setMapOption('disableDefaultUI', true);
        $map->setMapOption('disableDoubleClickZoom', true);
        $map->setMapOptions(array(
            'disableDefaultUI' => true,
            'disableDoubleClickZoom' => true
        ));

        $map->setStylesheetOption('width', '300px');
        $map->setStylesheetOption('height', '300px');
        $map->setStylesheetOptions(array(
            'width' => '300px',
            'height' => '300px'
        ));

        $map->setLanguage('fr');

        return array('map' =>$map);
    }
}

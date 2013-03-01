<?php
/**
 * Created by the fat IDE JetBrains PhpStorm.
 * User: Franck GORIN
 * Date: 01/03/13
 * Time: 14:43
 */

namespace LPDW\GoogleMapBundle\Manager;

use Ivory\GoogleMapBundle\IvoryGoogleMapBundle,
    Ivory\GoogleMapBundle\Model\MapTypeId,
    Ivory\GoogleMapBundle\Model\Map,
    Ivory\GoogleMapBundle\Model\Overlays\Marker,
    Ivory\GoogleMapBundle\Model\Overlays\Animation;

class GoogleMapManager
{

    protected $_oMap;
    protected $_oMarker;

    public function __construct(Map $_oMap, Marker $_oMarker){
        $this->_oMap = $_oMap;
        $this->_oMarker = $_oMarker;
    }

    public function buildMap($_iLatitude, $_iLongitude){

        $_oMap = $this->_oMap;

        // Configure your map options
        $_oMap->setPrefixJavascriptVariable('map_');
        $_oMap->setHtmlContainerId('map_canvas');

        $_oMap->setAsync(false);

        $_oMap->setAutoZoom(true);

        $_oMap->setCenter($_iLatitude, $_iLongitude, true);
        $_oMap->setMapOption('zoom', 4);

        $_oMap->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $_oMap->setMapOption('mapTypeId', MapTypeId::HYBRID);
        $_oMap->setMapOption('mapTypeId', 'terrain');

        $_oMap->setMapOptions(array(
            'disableDefaultUI' => true,
            'disableDoubleClickZoom' => true
        ));

        $_oMap->setStylesheetOptions(array(
            'width' => '900px',
            'height' => '600px'
        ));

        $_oMap->setLanguage('fr');

        return $_oMap;
    }

    public function createMarker($_aVenue){

        $_oMarker = $this->_oMarker;

        // Configure your marker options
        $_oMarker->setPrefixJavascriptVariable('marker_');

        $_oMarker->setPosition($_aVenue['latitude'], $_aVenue['longitude'], true);
        $_oMarker->setAnimation(Animation::DROP);
        $_oMarker->setOptions(array(
            'clickable' => true,
            'flat' => true
        ));

        return $_oMarker;
    }

}

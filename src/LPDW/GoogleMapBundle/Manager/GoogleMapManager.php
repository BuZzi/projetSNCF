<?php
/**
 * Created by the fat IDE JetBrains PhpStorm.
 * User: Franck GORIN
 * Date: 01/03/13
 * Time: 14:43
 */

namespace LPDW\GoogleMapBundle\Manager;

use Ivory\GoogleMapBundle\IvoryGoogleMapBundle,
    Ivory\GoogleMap\Map,
    Ivory\GoogleMap\MapTypeId,
    Ivory\GoogleMap\Overlays\Marker,
    Ivory\GoogleMap\Overlays\MarkerImage,
    Ivory\GoogleMap\Overlays\InfoWindow,
    Ivory\GoogleMap\Overlays\Animation;

class GoogleMapManager
{

    protected $_oMap;

    public function __construct(Map $_oMap){
        $this->_oMap = $_oMap;
    }

    public function buildMap($_iLatitude, $_iLongitude){

        $_oMap = $this->_oMap;

        // Configure your map options
        $_oMap->setPrefixJavascriptVariable('map_');
        //$_oMap->setHtmlContainerId('map_canvas');

        $_oMap->setAsync(false);

        $_oMap->setAutoZoom(true);

        $_oMap->setCenter($_iLatitude, $_iLongitude, true);
        //$_oMap->setMapOption('zoom', 4);

        //$_oMap->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $_oMap->setMapOption('mapTypeId', MapTypeId::ROADMAP);

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

    public function createMarker($_aVenue, $_mStationMarker=false){

        $_oMarker = new Marker();

        // Configure les options du marker
        $_oMarker->setPrefixJavascriptVariable('marker_');

        // Si c'est le marker de la station où nous nous trouvons, on créé une image spécifique
        if($_mStationMarker == true){
            $_oMarkerImage = new MarkerImage();

            // Configure les options du markerImage
            $_oMarkerImage->setPrefixJavascriptVariable('marker_image_');
            $_oMarkerImage->setUrl('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');
            //$_oMarkerImage->setAnchor(20, 34);
            //$_oMarkerImage->setOrigin(0, 0);
            //$_oMarkerImage->setSize(20, 34, "px", "px");
            //$_oMarkerImage->setScaledSize(20, 34, "px", "px");

            $_oMarker->setIcon($_oMarkerImage);
        }
        $_oMarker->setPosition($_aVenue['latitude'], $_aVenue['longitude'], true);
        $_oMarker->setAnimation(Animation::DROP);
        $_oMarker->setOptions(array(
            'clickable' => true,
            'flat' => true,
        ));

        return $_oMarker;
    }

    public function createInfoWindow($_aInfosContent){

        $_oInfoWindow = new InfoWindow();

        // Configure les options de la fenêtre d'infos
        $_oInfoWindow->setPrefixJavascriptVariable('info_window_');
        $_oInfoWindow->setPosition(0, 0, true);
        $_oInfoWindow->setPixelOffset(1.1, 2.1, 'px', 'pt');
    // A CONTINUER !!!
        var $_sContent = "<div class='infowindow'>"+
                            "<h4>$_aInfosContent['']</h4>"+
                            "{% if Venue.address is defined %}<span>{{ Venue.address }}</span><br>{% endif %}"+
                            "{% if Venue.postalCode is defined %}<span>{{ Venue.postalCode }}</span><br>{% endif %}"+
                            "{% if Venue.city is defined %}<span>{{ Venue.city }}</span><br>{% endif %}"+
                            "{% if Venue.state is defined %}<span>{{ Venue.state }}, {{ Venue.country }}</span><br>{% endif %}"+
                            "{% if Venue.distance is defined %}<span>Distance : {{ Venue.distance }} mètres</span>{% endif %}"+
                         "</div>";

        $_oInfoWindow->setContent($_sContent);

        $_oInfoWindow->setOpen(false);
        $_oInfoWindow->setAutoOpen(true);
        $_oInfoWindow->setOpenEvent(MouseEvent::CLICK);
        $_oInfoWindow->setAutoClose(false);
        $_oInfoWindow->setOptions(array(
            'disableAutoPan' => true,
            'zIndex'         => 10,
        ));

        return $_oInfoWindow;

    }

}

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
    Ivory\GoogleMapBundle\Model\Map;

class GoogleMapManager
{

    protected $_oMap;

    public function __construct(Map $_oMap){
        $this->_oMap = $_oMap;
    }

    public function buildMap(){

        $_oMap = $this->_oMap;

        // Configure your map options
        $_oMap->setPrefixJavascriptVariable('map_');
        $_oMap->setHtmlContainerId('map_canvas');

        $_oMap->setAsync(false);

        $_oMap->setAutoZoom(false);

        $_oMap->setCenter(0, 0, true);
        $_oMap->setMapOption('zoom', 3);

        $_oMap->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $_oMap->setMapOption('mapTypeId', MapTypeId::HYBRID);
        $_oMap->setMapOption('mapTypeId', 'terrain');

        $_oMap->setMapOption('disableDefaultUI', true);
        $_oMap->setMapOption('disableDoubleClickZoom', true);
        $_oMap->setMapOptions(array(
            'disableDefaultUI' => true,
            'disableDoubleClickZoom' => true
        ));

        $_oMap->setStylesheetOption('width', '300px');
        $_oMap->setStylesheetOption('height', '300px');
        $_oMap->setStylesheetOptions(array(
            'width' => '300px',
            'height' => '300px'
        ));

        $_oMap->setLanguage('fr');

        return $_oMap;
    }

}

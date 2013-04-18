<?php

namespace LPDW\TwitterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/twitter", name="lpdw_twitter")
     * @Template("LPDWTwitterBundle:Default:index.html.twig")
     */
    public function connectionAction()
    {
        $_oTwitter = $this->get('endroid.twitter');

        $_aParameters = array(
            'q' => '#appWhere',
        );

        // use the generic query method
        $_oResponse = $_oTwitter->query('search', 'GET', 'json', $_aParameters);
        $_oDatas = json_decode($_oResponse->getContent());

        var_dump($_oDatas);
        return array(
            'tweets' => $_oDatas
        );
    }
}

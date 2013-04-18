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
     * @Route("/connection", name="lpdw_twitterConnection")
     * @Template("LPDWTwitterBundle:Default:index.html.twig")
     */
    public function connectionAction()
    {
        $_oTwitter = $this->get('endroid.twitter');

        $_aParameters = array(
            'oauth_callback' => 'http://where.etuwebdev.fr'
        );

        // use the generic query method
        $response = $_oTwitter->query('oauth/request_token', 'POST', 'json', $_aParameters);
        $test = json_decode($response->getContent());

        //var_dump($test);
        return array();
    }
}

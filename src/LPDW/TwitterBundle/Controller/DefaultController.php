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
     * @Route("/connection")
     * @Template("LPDWTwitterBundle:Default:index.html.twig")
     */
    public function connectionAction($name)
    {
        $twitter = $this->get('endroid.twitter');

        // Retrieve the user's timeline
        $tweets = $twitter->getTimeline(array(
            'count' => 5
        ));

        return array('tweets' => $tweets);
    }
}

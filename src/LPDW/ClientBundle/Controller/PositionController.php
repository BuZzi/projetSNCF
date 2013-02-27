<?php

namespace LPDW\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use LPDW\SncfBundle\Entity\Station,
    LPDW\ClientBundle\Form\SearchStationType;

class PositionController extends Controller
{
    /**
     * @Route("/accueil")
     * @Template("LPDWClientBundle:Default:index.html.twig")
     */
    public function indexAction(Request $_oRequest)
    {
        $_oStation = new Station();
        $_oForm = $this->createForm(new SearchStationType(), $_oStation);

        if($_oRequest->isMethod('POST'))
        {
            $_oForm->bind($_oRequest);

            if($_oForm->isValid())
            {
                $_oStation = $_oForm->getData();

                // checker le nom rentré par l'utilisateur dans la base
                $em = $this->getDoctrine()->getManager();

                // redirect
                return array();
            }
        }

        // Render the form in the view
        return array(
            'form' => $_oForm->createView(),
        );
    }

    /**
     * Fonction ajax
     *
     * @Route ("/position", name="lpdw_position", options={"expose"=true})
     * @Template("LPDWClientBundle:Client:position.html.twig")
     */
    public function positionAction(Request $request)
    {
        $_mLatitude = $request->request->get('lat');
        $_mLongitude = $request->request->get('lng');

        $_sFormule = "(6366 * acos(cos(radians($_mLatitude))*cos(radians(`latitude`))*cos(radians(`longitude`) -radians($_mLongitude))+sin(radians($_mLatitude))*sin(radians(`latitude`))))";

        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT name, $_sFormule AS dist FROM station WHERE $_sFormule <= 10 ORDER by dist ASC");
        $statement->execute();
        $_aListStations = $statement->fetchAll();


        $product = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product')
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé avec id '.$id);
        }

        var_dump($_aListStations);
        return array(
                'latitude' => $_mLatitude,
                'longitude' => $_mLongitude,
                'listStations' => $_aListStations
        );
    }

}

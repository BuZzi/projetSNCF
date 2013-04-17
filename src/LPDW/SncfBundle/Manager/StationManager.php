<?php
/**
 * Created by ardietr
 * Date: 27/02/13
 * Time: 16:00
 */

namespace LPDW\SncfBundle\Manager;

use Doctrine\ORM\EntityManager;
use LPDW\SncfBundle\Manager\BaseManager;
use LPDW\SncfBundle\Entity\Station;


class StationManager extends BaseManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRepository()
    {
        //return $this->em->getRepository('LPDWSncfBundle:Station');
    }

    /**
     * Fonction qui check dans la base les stations les plus proches (rayon de 5 km)
     * de la position géographique passée en paramètre (couple latitude-longitude)
     *
     * @param $_mLatitude
     * @param $_mLongitude
     * @return array $_aListStations (id+distance par rapport au point passé en paramètre)
     */
    public function getAroundStation($_mLatitude, $_mLongitude)
    {
        // Formule qui trouve les points alentours en fonction du couple latitude-longitude
        $_sFormule = "(6366 * acos(cos(radians($_mLatitude))*cos(radians(`latitude`))*cos(radians(`longitude`) -radians($_mLongitude))+sin(radians($_mLatitude))*sin(radians(`latitude`))))";

        $connection = $this->em->getConnection();
        $statement = $connection->prepare(
                                    "SELECT id, $_sFormule AS dist
                                     FROM station
                                     WHERE $_sFormule <= 5
                                     ORDER by dist
                                     ASC
                                    ");
        $statement->execute();
        $_aListStations = $statement->fetchAll();

        return $_aListStations;
    }


    /**
     * @param $_sNameStation
     * @return array des stations correspondante à la recherche
     */
    public function findStationsByName($_sNameStation)
    {
        $_oQuery = $this->em->createQuery(
                                "SELECT s
                                 FROM LPDWSncfBundle:Station s
                                 WHERE s.name
                                 LIKE :word
                                ");
        $_oQuery->setParameter('word', '%'.$_sNameStation.'%');

        $_aListStations = $_oQuery->getArrayResult();

        // si plus de 15 résultats trouvées, renvoit false au controlleur
        if( count($_aListStations) >= 15 )
        {
            return false;
        }

        return $_aListStations;
    }
}

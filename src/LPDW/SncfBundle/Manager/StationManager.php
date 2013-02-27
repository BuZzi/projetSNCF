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

    public function getAroundStation($_mLatitude, $_mLongitude)
    {
        var_dump($_mLatitude);
        var_dump($_mLongitude);
        $_sFormule = "(6366 * acos(cos(radians($_mLatitude))*cos(radians(`latitude`))*cos(radians(`longitude`) -radians($_mLongitude))+sin(radians($_mLatitude))*sin(radians(`latitude`))))";

        //$em = $this->getRepository();

        $connection = $this->em->getConnection();
        $statement = $connection->prepare("SELECT id, $_sFormule AS dist FROM station WHERE $_sFormule <= 10 ORDER by dist ASC");
        $statement->execute();
        $_aListStations = $statement->fetchAll();

        //_oQuery = $em->createQuery("SELECT id, $_sFormule AS dist FROM station WHERE $_sFormule <= 10 ORDER by dist ASC");
        //$_aListStations = $_oQuery->getResult();

        //var_dump($_aListStations);

        /*
        $statement = $connection->prepare("SELECT id, $_sFormule AS dist FROM station WHERE $_sFormule <= 10 ORDER by dist ASC");
        $statement->execute();
        $_aListStations = $statement->fetchAll();
        */

        return $_aListStations;
    }







}

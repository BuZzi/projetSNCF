<?php
/**
 * Created by the fat IDE JetBrains PhpStorm.
 * User: Franck GORIN
 * Date: 26/02/13
 * Time: 13:29
 */

namespace LPDW\SncfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="station")
 */
class Station
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom ne doit pas être vide")
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $mailingAddress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $region;

    /**
     * @ORM\Column(type="decimal", scale=5)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale=5)
     */
    protected $longitude;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * get region
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * get mailing address
     * @return string
     */
    public function getMailingAddress()
    {
        return $this->mailingAddress;
    }

    /**
     * get latitude
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * get longitude
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * set name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * set region
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * set mailing address
     * @param string $mailingAddress
     */
    public function setMailingAddress($mailingAddress)
    {
        $this->mailingAddress = $mailingAddress;
    }

    /**
     * set latitude
     * @param string $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * set longitude
     * @param string $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Construct
     */
    public function __construct()
    {

    }

}
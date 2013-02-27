<?php
/**
 * Created by ardietr
 * Date: 27/02/13
 * Time: 11:39
 */

namespace LPDW\SncfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class StationSearch
{

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

<?php
/**
 * Created by ardietr
 * Date: 27/02/13
 * Time: 16:16
 */

namespace LPDW\SncfBundle\Manager;

abstract class BaseManager
{
    protected function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}

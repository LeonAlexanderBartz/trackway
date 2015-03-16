<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class GroupRepository
 *
 * @package AppBundle\Entity\Repository
 */
class GroupRepository extends EntityRepository
{
    /**
     * @param $name
     *
     * @return null|object
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}

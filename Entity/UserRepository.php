<?php
/**
 * Repository de l'entité de l'utilisateur
 * 
 * @author Olivier <sabinus52@gmail.com>
 * 
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Entity;

use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{

    /**
     * Retourne les utilisateurs appartenant au groupe spécifié
     * 
     * @param Group $group Groupe selectionné
     * @return array of User
     */
    public function findByGroup(Group $group)
    {
        $query = $this->createQueryBuilder('a')
                      ->innerjoin('a.groups', 'g')
                      ->where('g.id = :group')
                      ->setParameter('group', $group->getId());
        return $query->getQuery()->getResult();
     }

}

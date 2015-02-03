<?php
/**
 * Entité du groupe
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="olix_group")
 */
class Group extends BaseGroup
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 250,
     *                minMessage = "Le nom doit faire au moins {{ limit }} caractères",
     *                maxMessage = "Le nom ne peut pas être plus long que {{ limit }} caractères")
     */
    public function getName ()
    {
        return $this->name;
    }

}
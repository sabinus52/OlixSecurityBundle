<?php
/**
 *
 * Entité de l'utilisateur
 *
 *
 * @package Olix
 * @subpackage SecurityBundle
 * @author Olivier
 *
 */

namespace Olix\SecurityBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="olix_user")
 */
class User extends BaseUser {


    /**
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Son nom
     * 
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=250, nullable=true)
     */
    protected $name;

    /**
     * Son avatar
     * 
     * @var string
     * 
     * @ORM\Column(name="avatar", type="string", length=250, nullable=true)
     */
    protected $avatar;

    /**
     * Date de la dernière activité de l'utilisateur
     * 
     * @var \DateTime
     * 
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    protected $lastActivity;



    /**
     * Constructeur de l'utilisateur
     */
    public function __construct()
    {
        parent::__construct();
        $this->enabled = true;
        $this->avatar = 'default.png';
    }


    /**
     *Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     *Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        if (substr($this->avatar, 0, 4) == 'http') {
            return $this->avatar;
        } else {
            return 'bundles/olixsecurity/avatar/'.$this->avatar;
        }
    }


    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * @return User
     */
    public function setLastActivity(\DateTime $lastActivity)
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

}

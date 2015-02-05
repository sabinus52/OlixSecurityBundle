<?php
/**
 * Entité de l'utilisateur
 * 
 * @author Olivier <sabinus52@gmail.com>
 * 
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="Olix\SecurityBundle\Entity\UserRepository")
 * @ORM\Table(name="olix_user")
 * @UniqueEntity(fields = "username", message = "Ce login est déjà utilisé, merci d'en choisir un autre")
 * @UniqueEntity(fields = "email", message = "Cet email est déjà utilisé, merci d'en choisir un autre")
 */
class User extends BaseUser
{

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
     * 
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 250,
     *                minMessage = "Le nom doit faire au moins {{ limit }} caractères",
     *                maxMessage = "Le nom ne peut pas être plus long que {{ limit }} caractères")
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
     * @ORM\ManyToMany(targetEntity="Olix\SecurityBundle\Entity\Group")
     * @ORM\JoinTable(name="olix_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;


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
     * @see \FOS\UserBundle\Model\User::getUsername()
     * 
     * @Assert\NotBlank()
     * @Assert\Length(min = 2, max = 250,
     *                minMessage = "Le login doit faire au moins {{ limit }} caractères",
     *                maxMessage = "Le login ne peut pas être plus long que {{ limit }} caractères")
     * @Assert\Regex(pattern = "/^[A-Za-z][A-Za-z0-9\.\-\_]+$/",
     *               message = "Login invalide uniquement alphanumérique et/ou . - _")
     */
    public function getUsername ()
    {
        return $this->username;
    }


    /**
     * @see \FOS\UserBundle\Model\User::getEmail()
     * 
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Assert\Length(min = 2, max = 250,
     *                minMessage = "L'email doit faire au moins {{ limit }} caractères",
     *                maxMessage = "L'email ne peut pas être plus long que {{ limit }} caractères")
     */
    public function getEmail ()
    {
        return $this->email;
    }


    /**
     * Get expiresAt
     *
     * @return DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Retourne l'intervalle avant l'expiration
     * 
     * @return string
     */
    public function getExpiresInterval()
    {
        if (!$this->expiresAt) return '';
        $now = new \DateTime();
        $interval = $now->diff($this->expiresAt);
        if ($interval->days == 1)
            return $interval->format('%a jour');
        elseif ($interval->days > 1)
            return $interval->format('%a jours');
        elseif ($interval->h == 1)
            return $interval->format('%h heure');
        else
            return $interval->format('%h heures');
    }


    /**
     * Set name
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
     * Set avatar
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


    /**
     * Indique que l'utilisateur est en activité
     *
     * @return User
     */
    public function setOnline()
    {
        $this->setLastActivity(new \DateTime());
        return $this;
    }

    /**
     * Verifie si l'utilisateur est en activité
     *
     * @param integer $minDelay Minutes d'inactivité
     * @return boolean
     */
    public function isOnline($minDelay = 2)
    {
        $delay = new \DateTime();
        $strDelay = sprintf('%s minutes ago', $minDelay);
        $delay->setTimestamp(strtotime($strDelay));
        return ($this->getLastActivity() > $delay);
    }

    /**
     * Retourne le nombre de minutes écoulées depuis sa dernière activité
     * 
     * @return integer
     */
    public function getOnline()
    {
        if (!$this->getLastActivity()) return 9999;
        $now = new \DateTime();
        $interval = $now->diff($this->getLastActivity());
        return $interval->i;
    }


    /**
     * Retourne le temps écoulé depuis la dernière connexion
     *
     * @return string
     */
    public function getIntervalLastLogin()
    {
        if (!$this->lastLogin) return '';
        $now = new \DateTime();
        $interval = $now->diff($this->lastLogin);
        if ($interval->days == 1)
            return $interval->format('%a jour');
        elseif ($interval->days > 1)
            return $interval->format('%a jours');
        elseif ($interval->h == 1)
            return $interval->format('%h heure');
        elseif ($interval->h > 1)
            return $interval->format('%h heures');
        elseif ($interval->i == 1)
            return $interval->format('%i minute');
        else
            return $interval->format('%i minutes');
    }

}

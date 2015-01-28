<?php
/**
 * Formulaire du profil de l'utilisateur
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Avatar;

use \InvalidArgumentException;


class Gravatar
{

    /**
     * Constantes des URL des avatars
     */
    const HTTP_URL = 'http://www.gravatar.com/avatar/';
    const HTTPS_URL = 'https://secure.gravatar.com/avatar/';


    /**
     * Taille de l'avatar
     * @var integer
     */
    protected $size = 32;

    /**
     * Avatar par defaut URL externe ou ('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro')
     * @var string
     */
    protected $defaultImage = 'monsterid';

    /**
     * Rating par defaut (Valeur possible 'g', 'pg', 'r', 'x')
     * @var string
     */
    protected $rating = 'g';

    /**
     * Si utilisation du SSL
     * @var boolean
     */
    protected $secureUrl = false;



    /**
     * Retourne la taille courante de l'avatar
     * 
     * @return integer
     */
    public function getAvatarSize()
    {
        return $this->size;
    }


    /**
     * Affecte la taille de l'image
     * 
     * @param integer $size
     * @return Gravatar
     */
    public function setAvatarSize($size)
    {
        $this->size = intval($size);
        if ($this->size > 512 || $this->size < 0) {
            throw new InvalidArgumentException('Avatar size must be within 0 pixels and 512 pixels');
        }
        return $this;
    }


    /**
     * Retourne l'image par défaut
     * 
     * @return string
     */
    public function getDefaultImage()
    {
        return $this->defaultImage;
    }


    /**
     * Affecte l'image par défaut
     * 
     * @param string $image
     * @return Gravatar
     */
    public function setDefaultImage($image)
    {
        $imgLower = strtolower($image);
        
        $validDefaults = array('404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro');
        if (!in_array($imgLower, $validDefaults)) {
            // Verifie la bonne url
            if (!filter_var($image, FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException('The default image specified is not a recognized gravatar "default" and is not a valid URL');
            } else {
                $this->defaultImage = rawurlencode($image);
            }
        } else {
            $this->defaultImage = $imgLower;
        }
        
        return $this;
    }


    /**
     * Retroune le rating
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }


    /**
     * Affecte le rating
     * 
     * @param string $rating
     * @return Gravatar
     */
    public function setRating($rating)
    {
        $rating = strtolower($rating);
        $validRatings = array('g', 'pg', 'r', 'x');
        if (!in_array($rating, $validRatings)) {
            throw new InvalidArgumentException(sprintf('Invalid rating "%s" specified, only "g", "pg", "r", or "x" are allowed to be used.', $rating));
        }
        $this->rating = $rating;
        return $this;
    }


    /**
     * Verifie si on utilise le SSL
     * 
     * @return boolean
     */
    public function usingSecureImages()
    {
        return $this->secureUrl;
    }


    /**
     * Active le protocole SSL
     * 
     * @return Gravatar
     */
    public function enableSecureImages()
    {
        $this->secureUrl = true;
        return $this;
    }


    /**
     * Desactive le protocole SSL
     * 
     * @return Gravatar
     */
    public function disableSecureImages()
    {
        $this->secureUrl = false;
        return $this;
    }


    /**
     * Construit l'url de l'avatar à partir de l'émail
     * 
     * @param string $email
     * @return string
     */
    public function buildGravatarURL($email)
    {
        if ($this->usingSecureImages()) {
            $url = static::HTTPS_URL;
        } else {
            $url = static::HTTP_URL;
        }
        
        if (!empty($email)) {
            $url .= $this->getEmailHash($email);
        } else {
            $url .= str_repeat('0', 32);
        }
        
        return $url.$this->getGravatarParams($email);
    }


    /**
     * Construit et retourne les paramètres pour l'url de l'avatar
     * 
     * @param string $email
     * @return string
     */
    public function getGravatarParams($email)
    {
        $params = array();
        $params[] = 's=' . $this->getAvatarSize();
        $params[] = 'r=' . $this->getRating();
        if($this->getDefaultImage()) {
            $params[] = 'd=' . $this->getDefaultImage();
        }
        if (empty($email)) {
            $params[] = 'f=y'; // Force l'image par defaut
        }
        return (!empty($params)) ? '?'.implode('&', $params) : '';
    }


    /**
     * Retourne l'email avec hash
     * 
     * @param string $email
     * @return string
     */
    public function getEmailHash($email)
    {
        // Using md5 as per gravatar docs.
        return hash('md5', strtolower(trim($email)));
    }


    /**
     * @see Gravatar::buildGravatarURL()
     */
    public function get($email)
    {
        return $this->buildGravatarURL($email);
    }

}

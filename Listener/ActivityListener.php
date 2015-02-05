<?php
/**
 * Listener pour indiquer qu'un utilisateur est connecté ou pas
 * 
 * @author Olivier <sabinus52@gmail.com>
 * 
 * @package Olix
 * @subpackage SecurityBundle
 */

namespace Olix\SecurityBundle\Listener;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;

use Olix\SecurityBundle\Entity\User;


class ActivityListener
{

    /**
     * @var \Symfony\Component\Security\Core\SecurityContext
     */
    protected $context;
 
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Delai en minutes à partir duquel l'utilisateur est considéré comme non connecté
     * 
     * @var integer
     */
    protected $delay;



    /**
     * Constructeur
     * 
     * @param SecurityContext $context
     * @param EntityManager   $manager
     * @param integer         $delay Delai en minutes d'activité
     */
    public function __construct(SecurityContext $context, EntityManager $manager, $delay)
    {
        $this->context = $context;
        $this->em = $manager;
        $this->delay = $delay;
    }


    /**
     * Mets à jour le champs "lastActivity" de l'utilisateur à chaque requête
     * 
     * @param FilterControllerEvent $event
     * @return void
     */
    public function onCoreController(FilterControllerEvent $event)
    {
        // ici on vérifie que la requête est une "MASTER_REQUEST" pour que les sous-requêtes soient ingorées (par exemple si on fait un render() dans notre template)
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }
        
        // On vérifie qu'un token d'autentification est bien présent avant d'essayer manipuler l'utilisateur courant.
        if ($this->context->getToken()) {
            $user = $this->context->getToken()->getUser();
            
            // On utilise un délai pendant lequel on considère que l'utilisateur est toujours actif et qu'il n'est pas nécessaire de refaire de mise à jour
            $delay = new \DateTime();
            $strDelay = sprintf('%s minutes ago', $this->delay);
            $delay->setTimestamp(strtotime($strDelay));
            
            // On vérifie que l'utilisateur est bien du bon type pour ne pas appeler getLastActivity() sur un objet autre objet User
            if ($user instanceof User && $user->getLastActivity() < $delay) {
                $user->setOnline();
                $this->em->flush($user);
            }
        }
    }

}
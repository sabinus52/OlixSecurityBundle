Guide de démarrage
==================

### Traduction

Il faut activer le `translator` dans votre configuration.

``` yaml
# app/config/config.yml

framework:
    translator: ~
```

## Installation

L'installation se fait à 6 étapes :

1. Télécharger OlixSecurityBundle en utilisant composer
2. Activer le bundle
3. Configurer security.yml de votre application
4. Configurer OlixSecurityBundle (FOSUserBundle)
5. Importer les fichiers de routage
6. Mettre à jour le schema de votre base de données


### Etape 1 : Télécharger OlixSecurityBundle en utilisant composer

**Note** : Ne pas installer si utilisation du bundle *OlixAdminBundle*

Ajouter OlixSecurityBundle en executant la commande :

``` bash
$ php composer.phar require olix/security-bundle "~1"
```


### Etape 2 : Activer le bundle

Activer le bundle dans le kernel :

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new FOS\UserBundle\FOSUserBundle(),
        new Olix\SecurityBundle\OlixSecurityBundle(),
    );
}
```


### Etape 3 : Configurer security.yml de votre application

Le fichier `app/config/security.yml` contient la configuration de securité de votre application.

Exemple de configuration à adopter

``` yaml
# app/config/security.yml
security:

    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        
        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
            logout:       true
            anonymous:    true
            remember_me:
                key: "%secret%"

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/, role: ROLE_USER }
```

Pour plus d'information sur la configurion du fichier `security.yml`, lire la 
[documentation](http://symfony.com/doc/current/book/security.html) du composant sécurité de Symfony2.


### Etape 4 : Configurer OlixSecurityBundle (FOSUserBundle)

Ajouter le configuration suivante à votre fichier de configuration `app/config/config.yml` :

``` yaml
# app/config/config.yml
fos_user:
    db_driver: orm
    firewall_name: main
    user_class: Olix\SecurityBundle\Entity\User
    group:
        group_class: Olix\SecurityBundle\Entity\Group
```

### Etape 5 : Importer les fichiers de routage

**Note** : Le routage est déjà importé par défaut le bundle *OlixAdminBundle*.
Il est *inutile* d'ajouter cette configuration 

``` yaml
# app/config/routing.yml
olix_admin_security:
    resource: "@OlixSecurityBundle/Resources/config/routing.yml"
```


### Etape 6 : Mettre à jour le schema de votre base de données

Exécuter la commande suivante :

``` bash
$ php app/console doctrine:schema:update --force
```

### Etape 7 : Essayer

Vous pouvez maintenant vous connecter à `http://app.com/app_dev.php/login` !


## Tuning

Pour augmenter le temps de la session :

``` yaml
framework:
    session:
        cookie_lifetime: 0
        gc_maxlifetime: 30000
        # handler_id set to null will use default session handler from php.ini
        #handler_id:  ~
```
[Documentation Symfony](http://symfony.com/fr/doc/current/components/http_foundation/session_configuration.html#duree-de-vie-du-cookie-de-session)


## Pour plus d'informations

https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/index.md

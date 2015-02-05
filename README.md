OlixSecurityBundle
==================

Security bundle for Symfony2 with user management

Ce bundle est une surcharge du bundle [**FOSUserBundle**](https://github.com/FriendsOfSymfony/FOSUserBundle)

Elle s'incorpore uniquement dans le bundle de gestion de backend [**OlixAdminBundle**](https://github.com/sabinus52/OlixAdminBundle)

Fonctionnalités incluses :
- Gestion du profil + gravatar
- Gestion des utilisateurs
- Gestion des groupes
- Indicateur de connection

Les fonctionnalités suivantes ont été desactivées :
- Enregistrement d'un utilisateur lui-même
- Procédure de réinitialisation du mot de passe

**Note:** Ce bundle utilise le provider du coeur [SecurityBundle](http://symfony.com/doc/current/book/security.html).


### Pré-requis

Cette version a été testée à partir de la version 2.6 de Symfony, mais fonctionne pour les versions 2.3+.

Installation
------------

Les instructions de l'installation sont décrites dans la documentation stockée dans `Resources/doc/index.md`

[Lire la documentation](Resources/doc/index.md)

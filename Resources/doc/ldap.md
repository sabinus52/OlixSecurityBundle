Authentification avec un LDAP
=============================

Utilisation de [FR3DLdapBundle](https://github.com/Maks3w/FR3DLdapBundle) pour faire une authentification LDAP

### Pre-requis

Le module `php-ldap` est nécessaire
Pour information, ce bundle utilise *Zend Ldap v2*
.

## Installation

L'installation se fait à 6 étapes :

1. Ajout de FR3DLdapBundle en utilisant composer
2. Activer le bundle
3. Configurer security.yml de votre application
4. Configurer config.yml


### 1. Ajout de FR3DLdapBundle en utilisant composer

Ajouter FR3DLdapBundle en executant la commande :

``` bash
$ php composer.phar require fr3d/ldap-bundle "2.0.*@dev"
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
        new FR3D\LdapBundle\FR3DLdapBundle(),
    );
}
```

### Etape 3 : Configurer security.yml de votre application

Le fichier `app/config/security.yml` contient la configuration de securité de votre application.

Ajouter la chaine des providers avec `fos_userbundle` avant `fr3d_ldapbundle`.

**Ne pas oublier** : ajouter `fr3d_ldap` dans la section `firewall`

``` yaml
# app/config/security.yml
security:

    providers:
        chain_provider:
            chain:
                providers: [fos_userbundle, fr3d_ldapbundle]

        fr3d_ldapbundle:
            id: fr3d_ldap.security.user.provider

        fos_userbundle:
            id: fos_user.user_provider.username

    firewalls:
        main:
            fr3d_ldap: ~

```


### Etape 4 : Configurer config.yml

Ajouter le configuration suivante à votre fichier de configuration `app/config/config.yml` :

``` yaml
# app/config/config.yml
ffr3d_ldap:
    driver:
        host:                your.host.foo
#       port:                389    # Optional
#       username:            foo    # Optional
#       password:            bar    # Optional
#       bindRequiresDn:      true   # Optional
#       baseDn:              ou=users, dc=host, dc=foo   # Optional
#       accountFilterFormat: (&(uid=%s)) # Optional. sprintf format %s will be the username
#       optReferrals:        false  # Optional
#       useSsl:              true   # Enable SSL negotiation. Optional
#       useStartTls:         true   # Enable TLS negotiation. Optional
#       accountCanonicalForm: 3 # ACCTNAME_FORM_BACKSLASH this is only needed if your users have to login with something like HOST\User
#       accountDomainName: HOST
#       accountDomainNameShort: HOST # if you use the Backslash form set both to Hostname than the Username will be converted to HOST\User
    user:
        baseDn: ou=users, dc=host, dc=foo
        filter: (&(ObjectClass=Person))
        attributes:          # Specify ldap attributes mapping [ldap attribute, user object method]
#           - { ldap_attr: uid,  user_method: setUsername } # Default
#           - { ldap_attr: cn,   user_method: setName }     # Optional
#           - { ldap_attr: ...,  user_method: ... }         # Optional
#   service:
#       user_manager: fos_user.user_manager          # Overrides default user manager
#       ldap_manager: fr3d_ldap.ldap_manager.default # Overrides default ldap manager
```


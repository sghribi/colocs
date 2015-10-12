Installation en local
=====================


Prérequis
---------

 * `git `
 * `apache2` >= *2.4*
 * `php` >= *5.5*, avec les modules PHP suivants : `cli`, `pdo`, `curl`, `intl`, `xml`, `ldap`, `pgsql`, `ldap` et  `gd`
 * un serveur `PostgreSQL` >= 9.0.
 * `phpunit`, pour lancer les tests unitaires/fonctionnels
 * `bundler`, pour gérer les dépendances Ruby
 * `capifony`, pour déployer
 * Avoir/avoir accès à une machine avec une IP en 138.195.0.0/16

On Ubuntu/Debian, run :

``` bash
sudo aptitude install git apache2 php5 curl php5-ldap php5-gd php5-curl php5-cli php5-intl postgresql php5-pgsql bundler phpunit
bundle install --path=vendor
```

Configuration
-------------

### PHP

Il faut définir la timezone PHP et vérifier que les short tag sont désactivés. Dans *les* fichiers `php.ini` à la fois dans les dossiers `/etc/php5/apache2/` (et `/etc/php5/fpm/` si vous utilisez `php5-fpm`) et `/etc/php5/cli/` :

``` php.ini
date.timezone = Europe/Paris
short_open_tag = Off
```

Ne pas oublier de reloader Apache2 (et php-fpm si vous l'utilisez).

### Apache

 * Pour configurer Apache2, créer d'abord notre dossier de travail et le rendre accessible.

``` bash
# En www-data ou avec votre user (chown si nécessaire)
mkdir /var/www/colocs
```

Pour un **environnement de développement uniquement**, vous pouvez éviter de pénibles problèmes de droits en modifiant la conf d'Apache2 pour que l'environnement d'exécution soit lancé par un autre utilisateur que `www-data:www-data`.
Il suffit de modifier les variables d'environnement `APACHE_RUN_USER` et `APACHE_RUN_GROUP`, qui se situent sur Linux dans le fichier `/etc/apache2/envvars`.
Mettez-y l'utilisateur et le groupe du dossier `/var/www/colocs`. Ensuite, restarter le démon Apache2 ; si un message d'erreur apparaît, il faut `chown votre_user:root /var/lock/apache2`, puis réessayer.

Il s'agit en fait de la plus mauvaise solution recommandée par Symfony2, mais la plus rapide et efficace. Pour voir les autres manières de gérer correctement les droits : http://symfony.com/doc/master/book/installation.html#book-installation-permissions

 * Ensuite, créer un VirtualHost.

Par exemple sous Linux, créer dans `/etc/apache2/sites-available/` un fichier intitulé `colocs.conf` (/!\ sur certaines versions d'Apache2, l'extension `.conf` est nécessaire), qui contient :

``` apache
<VirtualHost *:80>
        DocumentRoot /var/www/colocs/web
        ServerName colocs.dev

       <Directory /var/www/colocs/web>
                AllowOverride None
                Options -Indexes +FollowSymLinks
                Require all granted

                <IfModule mod_rewrite.c>
                        RewriteEngine On
                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteRule ^(.*)$ app_dev.php [QSA,L]
                </IfModule>
        </Directory>
</VirtualHost>
```

Ensuite, créer un lien symbolique pour activer ce VirtualHost, à l'aide de la commande :

``` bash
# En root
a2ensite colocs.conf
```

 * Pour que ce vhost particulier fonctionne, il faut que le nom de domaine, colocs.dev, soit associé à la machine de dev, localhost. Il suffit pour cela de rajouter dans `/etc/hosts`

``` host
127.0.1.2 colocs.dev
```

 * Activer enfin le mode `rewrite` d'Apache (cela permet de ne pas avoir à écrire `app_dev.php` à la fin des URLs).

Par exemple sous Linux :

``` bash
# En root
a2enmod rewrite
service apache2 reload
```

### PostGreSQL

Accéder à une interface pour rentrer des commandes SQL (cli, phppgadmin, ou autres).
Par exemple sous Linux :

``` bash
# En root
su postgres
psql
```

Créer la base de données et l'utilisateur en lançant les commandes :

``` sql
CREATE DATABASE colocs ENCODING 'UTF-8';
CREATE USER colocs ENCRYPTED PASSWORD 'colocs';
GRANT ALL ON DATABASE colocs TO colocs;
```

### Certificats CACert et cURL

Il faut s'assurer d'avoir les certificats CACert sur sa machine et que cURL soit bien configuré pour les utiliser.

Installer ces certificats avec :

``` bash
sudo aptitude install ca-certificates
```

Ensuite, éditez le fichier `/etc/ca-certificates.conf` et y rajouter les lignes suivantes :

``` bash
# En root
cacert.org/root.crt
cacert.org/class3.crt
```

Puis, ajoutez les certificats et les réinstaller :

``` bash
cd /usr/share/ca-certificates/
sudo mkdir cacert.org && cd cacert.org
sudo wget "http://www.cacert.org/certs/root.crt"
sudo wget "http://www.cacert.org/certs/class3.crt"
sudo update-ca-certificates
```

Installation du projet
----------------------

Il faut avoir accès à :

 * https://github.com/sghribi/colocs
 
``` bash
cd /var/www/
git clone git@github.com:sghribi/colocs.git --recursive
```

### Vérification du système

Vérifier que notre système a tout ce dont il a besoin en lançant :

``` bash
php app/check.php
```

Corriger toutes les erreurs `Mandatory`, et en best-effort les recommendations `Optional`.


### Installation des dépendances

Télécharger toutes les dépendances en lançant la commande :

``` bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer install
```

Laisser les paramètres par défaut, ou adaptez-les si vous n'avez pas choisi la même configuration.


### Initialisation des dépendances CSS/JS

Désormais, il reste quelques commandes Symfony pour préparer le site.

``` bash
# Préparation des données du site
php app/console assets:install --symlink web
php app/console assetic:dump
```

### Initialisation de la BDD

``` bash
php app/console doctrine:fixtures:load --purge-with-truncate
```

### Accès au LDAP du CTI (optionnel si vous avez une IP en 138.195.0.0/16)

Créer une redirection de port :

``` bash
sudo ssh -L 389:<machine>.ecp.fr:389 <user>@<machine>.ecp.fr
```

Puis, rajouter dans `app/config/parameters.yml` :

``` yml
parameters:
    # Other parameters...

    ldap_host: localhost
```

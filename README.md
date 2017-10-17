Plasmide
========

CMS PHP sans base de données

- Site :	http://plasmide.selenith.ovh
- Version :	3.3.1
- Date : 	2017 - 10
- Auteur : 	Selenith - http://selenith.ovh


Presentation
============

Plasmide CMS (Content Management System) est un Systeme de gestion de contenu dont le maitre mot est la simplicité. Ses grandes forces sont sa modularité et le fait qu'il fonctionne sans base de données ! 

Vous allez pouvoir créer, personnaliser et mettre en ligne votre site  sans avoir besoin de compétences techniques particulières. Fini les configurations longues et contraignante, fini la traque aux identifiants permettant l'acces a des bases de données.

De plus Plasmide est disponible sous licence GNU_GPL v3. Vous pouvez donc le télécharger, l'utiliser et le modifier gratuitement !

Specifications
==============

La version actuelle de plasmide contient les caracteristiques suivantes :

- Fonctionne sans base de donnée
- Gestion d'utilisateurs et de droits multiples.
- Gestions et upload de media simplifiés.
- Articles commentables.
- Flux RSS.
- Module de contact disponible  https://github.com/selenith/plasmide-contact
- Module de forum disponible https://github.com/selenith/plasmide-forum
- Module de raidplanner disponible https://github.com/selenith/plasmide-raidplanner
- Module de wiki disponible https://github.com/selenith/plasmide-codex

Prés-requis
===========
- Votre serveur web doit disposer de PHP version 5.2 minimum.
- La bibliotheque GD ou ImageMagic doit etre activée pour PHP.
- Avoir acces a la configuration de votre serveur web

Installation
===========

Methode automatique : 

```
wget https://raw.githubusercontent.com/selenith/plasmide/master/plasmide.sh
chmod +x plasmide.sh
./plasmide.sh install defaut
```
Methode Manuelle : 
Apres avoir télécharger le fichier ZIP sur https://github.com/selenith/plasmide, decompressez le et placez le contenu dans le dossier de publication de votre serveur (habituellement /var/www/).
Pensez à donner les droits en ecriture au systeme dans les dossiers files/, core/data/, tools/HTMLPurifier, et mods/[nom du mod]/data/.

Configuration de NGINX
===========
Modifiez la configuration du fichier correspondant à votre site dans /etc/nginx/sites-enabled/ :

```
location / {
    try_files $uri /router.php$is_args$args;
        
}



# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php5-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

}


location ~ ^/router\.php(/|$) {
	fastcgi_pass unix:/var/run/php5-fpm.sock;
	fastcgi_split_path_info ^(.+\.php)(/.*)$;
	include fastcgi_params;
	# When you are using symlinks to link the document root to the
	# current version of your application, you should pass the real
	# application path instead of the path to the symlink to PHP
	# FPM.
	# Otherwise, PHP's OPcache may not properly detect changes to
	# your PHP files (see https://github.com/zendtech/ZendOptimizerPlus/issues/126
	# for more information).
	fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
	fastcgi_param DOCUMENT_ROOT $realpath_root;
	# Prevents URIs that include the front controller. This will 404:
	# http://domain.tld/app.php/some-path
	# Remove the internal directive to allow URIs like this
	internal;
}   
```

Administration
===========
Rendez vous à la page d'administration : http(s)://votreDomaine/admin (remplacer "votreDomaine" par l'ip ou le nom de domaine de votre serveur).

- Login : admin
- Password : plasmide

Rendez vous ensuite dans l'onglet 'Parametres' afin d'indiquer les parametres de base de votre site.


mise a jour
===========
Lancer la commande suivante via le script plasmide.sh
```
./plasmide.sh update
```


Configuration des modules
===========
Referez vous aux fichiers readme.md dans les dossiers des modules pour plus d'infos.

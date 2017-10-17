#!/bin/bash

# Parametres
webServerUser='www-data'
sourceFile='master.zip'
sourceURL='https://github.com/selenith/plasmide/archive/'$sourceFile
urlModInstall='https://raw.githubusercontent.com/selenith/'
unzipFileName='plasmide-master'
sourceVersionURL='https://raw.githubusercontent.com/selenith/plasmide/master/README.md'



#couleurs pour le texte
noir='\e[0;30m'
gris='\e[1;30m'
rougefonce='\e[0;31m'
rose='\e[1;31m'
vertfonce='\e[0;32m'
vertclair='\e[1;32m'
orange='\e[0;33m'
jaune='\e[1;33m'
bleufonce='\e[0;34m'
bleuclair='\e[1;34m'
violetfonce='\e[0;35m'
violetclair='\e[1;35m'
cyanfonce='\e[0;36m'
cyanclair='\e[1;36m'
grisclair='\e[0;37m'
blanc='\e[1;37m'

neutre='\e[0;m'


# Met a jour plasmide et appelle les scripts de mises a jour des modules
update(){

    localVersion=$(grep Version README.md)
    sourceVersion=$(curl -s $sourceVersionURL |grep Version)
    retval=$?
    if [ $retval != 0 ]; then
        echo Impossible de lire le fichier distant. Verifiez votre connexion réseau.
        echo -e  $rougefonce'Arret de la procedure de mise a jour.'$neutre
        exit 0

    fi

    echo -e Verification de la necessité de mise a jour.

    if [[ $sourceVersion == $localVersion ]]; then
        echo -e $vertclair'Plasmide est a jour'$neutre
        mkdir plasmide
    else
        echo -e $orange'Plasmide necessite une mise a jour'$neutre
        echo -e 'Téléchargement de la nouvelle version'
        wget $sourceURL
        unzip -q $sourceFile
        mv $unzipFileName plasmide


        for fichier in $(ls)
        do
            if [[ $fichier != 'files' && $fichier != 'data' && $fichier != 'plasmide' && $fichier != 'templates' && $fichier != 'feed' && $fichier != 'mods' ]]; then
                rm -Rf $fichier
                echo -e $jaune'suppression de '$fichier''$neutre
            fi
        done
        

        rm -Rf plasmide/files
        rm -Rf plasmide/data/*/*
        rm -Rf plasmide/templates
        rm -Rf plasmide/feed
        rm -Rf plasmide/mods
        
        echo -e Copie des nouveaux fichiers.
        cp -r plasmide/* ./
        chmod +x plasmide.sh
        chown $webServerUser:$webServerUser -R ./*
       echo -e Fin de mise a jour du noyau plasmide.
    fi

    echo -e $cyanclair'Début de procédure de mise a jour des modules'$neutre
    for dossier in $(ls mods)
    do
        if [ -x  mods/$dossier/module.sh ]; then
            mods/$dossier/module.sh update

        else
            echo -e $orange'le module '$dossier' ne possede pas de script de mise a jour ou celui ci n est pas executable.'$neutre
        fi
    done
    echo -e $cyanclair'Fin de procédure de mise a jour des modules'$neutre
    echo -e Nettoyage des fichiers temporaires
    rm -rf plasmide
    rm $sourceFile
    echo -e $vertclair'Mise a jour terminée.'$neutre

}

# $1 = installCommande
install(){

    if [[ $1 == 'defaut' ]]; then
        echo -e 'Installation de Plasmide par defaut.'
        echo -e 'Les modules admin, auth, news, site vont etre installés.'
        echo -e 'Recuperation du noyau'
        wget $sourceURL
        echo -e 'Decompression'
        unzip -q $sourceFile
        cp -r $unzipFileName/* ./
        chmod +x plasmide.sh
        chown $webServerUser:$webServerUser -R ./*
        rm -Rf $unzipFileName
        rm $sourceFile
        echo -e "Fin d'installation du noyau Plasmide."

    else
        echo -e 'Le module '$1' va etre installé.'
        wget $urlModInstall'plasmide-'$1'/module.sh'

        if [ -f  module.sh ]; then
            chmod +x module.sh
            mkdir mods/$1/
            mv module.sh mods/$1/
            mods/$1/module.sh install
        else
            echo -e $orange'le fichier d'"'"'insatllation du module n'"'"'a pas pu etre téléchargé. Arret de la procédure.'$neutre
        fi                                                        

    fi
    
}

aide(){
    echo -e 'plasmide.sh : operande manquante'
    echo -e 'Utilisation : ./plasmide.sh [update|install]'
}

aideInstall(){
    echo -e 'plasmide.sh install : operande manquante'
    echo -e 'Utilisation : ./plasmide.sh install [defaut|nom du Module]'
}

# ================= MAIN =======================

#verification des droits
if [[ $EUID -ne 0 ]]; then
  echo "Vous devez etre root ou disposer des droits superutilisateur pour executer ce script" 2>&1
  exit 1
fi

# placement dans le bon repertoire
execPath=$(readlink -f $(dirname $0))
cd $execPath


if [ $# == 0 ]; then
    aide
    exit 0
fi

if [[ $1 == 'install' ]]; then
    
    if [[ $# < 2 ]]; then                  
        aideInstall
    else
        install $2 
    fi
elif [[ $1 == 'update' ]];then
    update
fi




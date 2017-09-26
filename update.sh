#!/bin/bash

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



#verification des droits
if [[ $EUID -ne 0 ]]; then
  echo "Vous devez etre root ou disposer des droits superutilisateur pour executer ce script" 2>&1
  exit 1
fi

# placement dans le bon repertoire
execPath=$(readlink -f $(dirname $0))
cd $execPath

sourceVersion=$(curl -s https://raw.githubusercontent.com/selenith/plasmide/master/README.md |grep Version)

retval=$?
if [ $retval != 0 ]; then
    echo Impossible de lire le fichier distant. Verifiez votre connexion réseau.
    echo -e  $rougefonce'Arret de la procedure de mise a jour.'$neutre
    exit 0

fi


localVersion=$(grep Version README.md)

echo -e Verification de la necessité de mise a jour.

if [[ $sourceVersion == $localVersion ]]; then
    echo -e $vertclair'Plasmide est a jour'$neutre
    mkdir plasmide
else
    echo -e $orange'Plasmide necessite une mise a jour'$neutre
    echo -e 'Téléchargement de la nouvelle version'
    git clone https://github.com/selenith/plasmide



    for fichier in $(ls)
    do
        if [[ $fichier != 'files' && $fichier != 'core' && $fichier != 'plasmide' && $fichier != 'templates' && $fichier != 'feed' && $fichier != 'mods' ]]; then
            rm -Rf $fichier
            echo -e $jaune'suppression de '$fichier''$neutre
        fi
    done
    
    for fichier in $(ls core)
    do
        if [[ $fichier != 'data' ]]; then
             rm -Rf $fichier
            echo -e $jaune'suppression de core/'$fichier''$neutre
        fi
    done

    rm -Rf plasmide/files
    rm -Rf plasmide/core/data/*/*
    rm -Rf plasmide/templates
    rm -Rf plasmide/feed
    rm -Rf plasmide/mods
    
    echo -e Copie des nouveaux fichiers.
    cp -r plasmide/* ./
    chmod +x update.sh
    echo -e Fin de mise a jour du noyau plasmide.
fi

echo -e $cyanclair'Début de procédure de mise a jour des modules'$neutre
for dossier in $(ls mods)
do
    if [ -x  mods/$dossier/update.sh ]; then
        mods/$dossier/update.sh

    else
        echo -e $orange'le module '$dossier' ne possede pas de script de mise a jour ou celui ci n est pas executable.'$neutre
    fi
done
echo -e $cyanclair'Fin de procédure de mise a jour des modules'$neutre
echo -e Nettoyage des fichiers temporaires
rm -rf plasmide
echo -e $verclair'Mise a jour terminée.'$neutre

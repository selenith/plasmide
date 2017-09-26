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

modName='news'
repoName='plasmide-'$modName

echo -e Lancement de la procedure de mise à jour du module $modName

#verification des droits
if [[ $EUID -ne 0 ]]; then
  echo "Vous devez etre root ou disposer des droits superutilisateur pour executer ce script" 2>&1
  exit 1
fi

# placement dans le bon repertoire
execPath=$(readlink -f $(dirname $0))
cd $execPath

sourceVersion=$(curl -s https://raw.githubusercontent.com/selenith/$repoName/master/README.md |grep Version)

retval=$?
if [ $retval != 0 ]; then
    echo Impossible de lire le fichier distant. Verifiez votre connexion réseau.
    echo -e  $rougefonce'Arret de la procedure de mise a jour.'$neutre
    exit 0

fi


localVersion=$(grep Version README.md)

# echo -e Verification de la necessité de mise a jour.

if [[ $sourceVersion == $localVersion ]]; then
    echo -e $vertclair'Le module '$modName' est à jour'$neutre
else
    echo -e $orange$modName' necessite une mise a jour'$neutre
    
    for fichier in $(ls)
    do
        if [[ $fichier != 'data' && $fichier != 'config.php' ]]; then
             rm -Rf $fichier
        fi
    done

    echo -e 'Téléchargement de la nouvelle version'
    git clone https://github.com/selenith/$repoName

    rm -Rf $repoName/data/*/*

    if [ -f $repoName/config.php ]; then
        rm  $repoName/config.php
    fi
    
    # echo -e Copie des nouveaux fichiers.
    cp -r $repoName/* ./
    chmod +x update.sh

    echo -e Nettoyage des fichiers temporaires
    rm -rf $repoName
    echo -e $vertclair'Mise a jour du module '$modName' terminée.'$neutre
fi


#!/bin/bash

modName='news'
DBTables=()
webServerUser='www-data'
repoName='plasmide-'$modName
sourceVersionURL='https://raw.githubusercontent.com/selenith/'$repoName'/master/README.md'
sourceFile='master.zip'
sourceURL='https://github.com/selenith/'$repoName'/archive/'$sourceFile
unzipFileName=$repoName'-master'

createDB(){
	echo -e 'Pas de data a traiter pour ce module.'
}

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




update(){
    localVersion=$(grep Version README.md)
    sourceVersion=$(curl -s $sourceVersionURL |grep Version)
    retval=$?
    if [ $retval != 0 ]; then
        echo Impossible de lire le fichier distant. Verifiez votre connexion réseau.
        echo -e  $rougefonce'Arret de la procedure de mise a jour.'$neutre
        exit 0

    fi
    
    # echo -e Verification de la necessité de mise a jour.

    echo -e Lancement de la procedure de mise à jour du module $modName
    if [[ $sourceVersion == $localVersion ]]; then
        echo -e $vertclair'Le module '$modName' est à jour'$neutre
    else
        echo -e $orange$modName' necessite une mise a jour'$neutre
        

        echo -e 'Téléchargement de la nouvelle version'
        wget $sourceURL 
        unzip -q $sourceFile
        mv $unzipFileName update

        if [ -f update/config.php ]; then
            rm  update/config.php
        fi

        cp -r update/* ./
        chmod +x module.sh
        chown $webServerUser:$webServerUser -R ./*
                        
        # echo -e Copie des nouveaux fichiers.

        echo -e Nettoyage des fichiers temporaires
        rm -rf update
        echo -e $vertclair'Mise a jour du module '$modName' terminée.'$neutre
    fi
}


install(){
    
    if [ ! -f README.md ]; then
        echo -e 'Téléchargement du module '$modName
        wget $sourceURL 
        unzip -q $sourceFile
        mv $unzipFileName install


        cp -r install/* ./
        chmod +x module.sh
        chown $webServerUser:$webServerUser -R ./*
    fi
    for table in ${DBTables[@]}
    do
        mkdir '../../data/'$table
        touch '../../data/'$table'.php'
        createDB $table
        chown $webServerUser:$webServerUser -R ../../data/$table
        chown $webServerUser:$webServerUser ../../data/$table'.php'
    done 
                        
    # echo -e Copie des nouveaux fichiers.
        
    echo -e Nettoyage des fichiers temporaires
    rm -rf install
    echo -e $vertclair'installation du module '$modName' terminée.'$neutre
}

remove(){
    for table in ${DBTables[@]}
    do
        rm -Rf '../../data/'$table
        rm '../../data/'$table'.php'
    done 

   rm -Rf ../$modName

   echo -e $vertclair'Suppression du module '$modName' terminée.'$neutre
}

aide(){
    echo -e 'module.sh : operande manquante'
    echo -e 'Utilisation : ./module.sh [update|install|remove]'
}



# ====================== MAIN ==============================
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
    install 
elif [[ $1 == 'update' ]]; then
    update
elif [[ $1 == 'remove' ]]; then
    remove
fi


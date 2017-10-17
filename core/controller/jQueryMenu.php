<?php

include('../metier/Archivable.php');
include('../metier/Archiviste.php');
include('../metier/Ribosome.php');


//########################################################################################################


$arch = new Archiviste();
$menu = new Archivable('Menu');
$menus = $arch->restituer($menu);
$menus = $arch->trier($menus, 'ordre', true);



$nbMenu = count($menus);
$json = array('statut'=>'ok');
$json['menus']=array();
for($i=0; $i< $nbMenu; $i ++){
	
	$json['menus'][$i] = array(
	'id'=>strval($menus[$i]->get('id')),
	'mod'=>$menus[$i]->get('mod'),
	'idParent'=>$menus[$i]->get('id_parent'),
	'nom'=>$menus[$i]->get('nom')	
	);	
}

echo(json_encode($json));
?>

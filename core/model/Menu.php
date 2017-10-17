<?php

class Menu{

	public static function lister(){
		$arch = new Archiviste();
		$menu = new Archivable('Menu');
		$menus = $arch->restituer($menu);
		$menus = $arch->trier($menus, 'ordre', true);

                $nbMenu = count($menus);
                $displayId;

                for($i=0; $i<$nbMenu; $i++){

                    $displayId = false;
                    if(file_exists('mods/'.$menus[$i]->get('mod').'/hook.php')){
                        include('mods/'.$menus[$i]->get('mod').'/hook.php');

                        if($displayId){
                            $menus[$i]->set('idString', '/'.$menus[$i]->get('id'));
                        }else{
                            $menus[$i]->set('idString', '');
                        }
                    }                    
                }
	
		return $menus;
	}
	
	//----------ajoutmeta
	public static function getDescription($idMenu){
		$metaDesc= false;
		$arch = new Archiviste();
		$menu = new Archivable('Menu');
		$menu->set('id', $idMenu);
		$menus = $arch->restituer($menu);
		
		if(count($menus)== 1){
			$menu = $menus[0];
		}
		
		$metaDesc = $menu->get('metaDesc');
		
		return $metaDesc;
	}
	//----------finmeta
	
	public static function getSyle($idMenu){
		$style= false;
		$arch = new Archiviste();
		$menu = new Archivable('Menu');
		$menu->set('id', $idMenu);
		$menus = $arch->restituer($menu);
		
		if(count($menus)== 1){
			$menu = $menus[0];
		}
		
		$style = $menu->get('stylePage');
		
		return $style;
	}

	public static function getNom($idMenu){
		$style= false;
		$arch = new Archiviste();
		$menu = new Archivable('Menu');
		$menu->set('id', $idMenu);
		$menus = $arch->restituer($menu);
		
		if(count($menus)== 1){
			$menu = $menus[0];
		}
		
		$nom = $menu->get('nom');
		
		return $nom;
	}
	
	public function filAriane($id){
		$arch = new Archiviste();
		
		return $this->menuParent($id, $arch);
	}
	
	public function getNewsMenu(){
		$arch = new Archiviste();
		$menu = new Archivable('Menu');
		$menu->set('mod', 'news');
		$menus = $arch->restituer($menu);
		
		$menu=false;
		if(count($menus)>0){
			$menu= $menus[0];
		}
	
		return $menu;
	}
	
	
	private function menuParent($id, $arch){
	
		$menu = new Archivable('Menu');
		$menu->set('id', $id);
		$menus = $arch->restituer($menu);
		if(count($menus)>0){
			$parent = $this->menuParent($menus[0]->get('id_parent'),$arch );
			if(count($parent)>0){
				$menus =array_merge($parent,  $menus);
			}
			
		}
		return $menus;
	}
	
}

?>

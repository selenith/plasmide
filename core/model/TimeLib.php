<?php
class TimeLib{
	
	public static function dateToTime($date, $heure){
		$time= false;
		$h = $m = '';
		
		$jma = explode("-", $date);
		$hm = explode(":", $heure);
		
		date_default_timezone_set('Europe/Paris'); 
		
		if(count($jma==3) && count($hm) == 2){
			
			if(strlen($jma[0])==4){
				$dateTemp = strptime($date, '%Y-%m-%d');
				
				
			}else if(strlen($jma[2])==4){
				$dateTemp = strptime($date, '%d-%m-%Y');
				
			}else{
				return $time;
			}
			
			
			$h = $hm[0];
			$m = $hm[1];
			
			
			$time = mktime($h, $m, 1, $dateTemp['tm_mon']+1, $dateTemp['tm_mday'], $dateTemp['tm_year']+1900);
		}
		
		
		
		return $time;
	}
	
	
	public static function timeToDate($timestamp){
		
		date_default_timezone_set('Europe/Paris'); 
		
		return date('d-m-Y \à H:i', $timestamp);
	}
	
	public static function timeToDay($timestamp){
		
		date_default_timezone_set('Europe/Paris'); 
		
		return date('Y-m-d', $timestamp);
	}
	
	public static function timeToHour($timestamp){
		
		date_default_timezone_set('Europe/Paris'); 
		
		return date('H:i', $timestamp);
	}

}

?>

<?php
class Securisator{
    
    public static function checkIntrusion($html){
        
       return preg_match('/<\/*\s*script.*>/',  $html);
    }
    
    
}
?>
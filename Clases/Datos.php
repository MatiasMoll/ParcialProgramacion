<?php

class Data{

    public static function SaveSerialized($file,$object){

        $response = false;
        $pFile = fopen($file,'a');
        if(!is_null($pFile)){
            $rta = fwrite($pFile,serialize($object).PHP_EOL);
            if($rta > 0){
                $response = true;
            }
            fclose($pFile);
        }
        return $response;
    }

    public static function LoadSerialized($file){
        
        $response = false;
        if(file_exists($file)){
            $pFile = fopen($file,'r');
            if(!is_null($pFile)){
                $response = array();
                while(!feof($pFile)){
                    array_push($response,unserialize(fgets($pFile)));
                }           
                fclose($pFile);
                array_pop($response);
           }
        }
        return $response;
    }

    public static function SaveJson($file, $object){
        $response = false;      
        $pFile = fopen($file,'w');
        if(!is_null($pFile)){
            $rta = fwrite($pFile,json_encode($object));
            if($rta > 0 ){
                $response = true;
            }
        }
        return $response;
    }

    public static function LoadJson($file){
        $response = false;
        if(file_exists($file)){
            $pFile = fopen($file,'r');
            if(!is_null($pFile)){
                    $response = json_decode(fread($pFile,filesize($file)));
            }
        }   
        return $response;
    }

}
<?php

class Profesores{

    public $nombre;
    public $legajo;
    public $foto;

    public function __construct($nombre, $legajo, $foto)
    {
        $this->nombre = $nombre;
        $this->legajo = $legajo;
        $this->foto  = $foto;
    }

    public static function CargarProfesor($nombre,$legajo,$foto){
        $lstProfesores = Data::LoadSerialized("profesores.txt");
        $creo = true;
        if($lstProfesores){
            foreach($lstProfesores as $profe){
                if($profe->legajo == $legajo){
                    $creo = false;
                break;
                }
            }
        }
        if($creo){
            $nuevoProfesor = new Profesores($nombre,$legajo,$foto);
            Data::SaveSerialized("profesores.txt",$nuevoProfesor);
            $nombreArchivo = time().'-'.$foto['name'];
            $tmp_name = $foto['tmp_name'];
            $folder= 'imagenes/';
            move_uploaded_file($tmp_name,$folder.$nombreArchivo);
        }
        return $creo;
    }

    public static function BuscarProfesor($legajo){
        $respuesta = Data::LoadSerialized('profesores.txt');
        $rta = false;
        if($respuesta){
            foreach($respuesta as $profesor){
                if($profesor->legajo == $legajo){
                    $rta = $profesor->nombre.' '.$profesor->legajo;
                    break;
                }
            }

        }
        return $rta;

    }
    public static function ListarProfesores(){
        $respuesta = Data::LoadSerialized('profesores.txt');
        $rta = 'Profesores: '.PHP_EOL;
        if(!$respuesta){
            $respuesta = "No se han cargado profesores todavia";
        }else{
            foreach ($respuesta as  $materia) {
                $rta = $rta.$materia->nombre.PHP_EOL.
                        $materia->legajo.PHP_EOL.
                        '------------------------------'.PHP_EOL;
            }
            $respuesta = $rta;
        }
        return $respuesta;
    }
    
}
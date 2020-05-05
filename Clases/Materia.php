<?php

class Materia{

    static $IdPrivado = 0;
    public $nombre;
    public $cuatrimestre;
    public $id;

    public function __construct($nombre,$cuatrimestre){
        $this->nombre=$nombre;
        $this->cuatrimestre=$cuatrimestre;
        $this->id = Materia::$IdPrivado + 1;
        Materia::$IdPrivado = $this->id;
    }
    
    public static function BuscarMateria($id){
        $respuesta = Data::LoadSerialized('materias.txt');
        if($respuesta){
            foreach($respuesta as $materia){
                if($materia->id == $id){
                    $respuesta = $materia->nombre;
                }
            }
        }
        return $respuesta;

    }
    public static function ListarMaterias(){
        $respuesta = Data::LoadSerialized('materias.txt');
        $rta = 'Materias: '.PHP_EOL;
        if(!$respuesta){
            $respuesta = "No se han cargado materias todavia";
        }else{
            foreach ($respuesta as  $materia) {
                $rta = $rta.$materia->nombre.PHP_EOL.
                        $materia->cuatrimestre.PHP_EOL.
                        '---------------------------------'.PHP_EOL;
            }
            $respuesta = $rta;
        }
        return $respuesta;
    }
}
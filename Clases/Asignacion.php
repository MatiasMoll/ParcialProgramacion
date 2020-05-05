<?php

require_once __DIR__.'/Materia.php';
require_once __DIR__.'/Profesores.php';

class Asignacion{

    public $legajoProfesor;
    public $idMateria;
    public string $turno;

    public function __construct($legajo,$id,$turno)
    {
        $this->legajoProfesor = $legajo;
        $this->idMateria = $id;
        $this->turno = $turno;
    }

    public  static function CrearAsignacion($legajo,$id,$turno){
        $lstAsignacion = Data::LoadSerialized("materias-profesores.txt");
        $creo = true;
        if($lstAsignacion){
            foreach($lstAsignacion as $profe){
                $profeExistente = Profesores::BuscarProfesor($profe->legajoProfesor);
                $materiaExistente = Materia::BuscarMateria($profe->idMateria);
                if((!$profeExistente || !$materiaExistente) ||
                   ($profe->legajo == $legajo && $profe->idMateria == $id && $profe->turno == $turno)){
                    $creo = false;
                break;
                }
            }
        }
        if($creo){
            $nuevaAsignacion = new Asignacion($legajo,$id,$turno);
            Data::SaveSerialized("materias-profesores.txt",$nuevaAsignacion);
        }
        return $creo;
    }

    
    public static function MostrarAsignacion(){
        $respuesta = Data::LoadSerialized('materias-profesores.txt');
        $rta = 'Asignaciones y sus Profesores: '.PHP_EOL;
        if(!$respuesta){
            $respuesta = "No se han cargado asignaciones  todavia";
        }else{
            foreach ($respuesta as  $materia) {
                $profe = Profesores::BuscarProfesor($materia->legajoProfesor);
                $mate = Materia::BuscarMateria($materia->idMateria);
                if($profe && $mate){
                    $rta = $rta.'Materia: '.$mate.PHP_EOL.
                            'Profesor: '.$profe.PHP_EOL;
                }
            }
            $respuesta = $rta;
        }
        return $respuesta;
    }
}
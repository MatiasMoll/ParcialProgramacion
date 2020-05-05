<?php

use \Firebase\JWT\JWT;

require_once __DIR__.'/Clases/Usuarios.php';
require_once __DIR__.'/Clases/productos.php';
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/Clases/Materia.php';
require_once __DIR__.'/Clases/Datos.php';
require_once __DIR__.'/Clases/Profesores.php';
require_once __DIR__.'/Clases/Asignacion.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$pathInfo = $_SERVER['PATH_INFO'];



switch($requestMethod)
{
    case 'POST':
            switch($pathInfo)
            {
                case '/usuario': 
                    if(isset($_POST['email'])&& isset($_POST['clave'])){
                        
                        if(Users::SigIn($_POST['clave'],$_POST['email'])){
                            echo "Usuario Creado Correctamente";
                        }else{
                            echo "Error en los campos";
                        }
                    }
                break;
                case '/login':                     
                    $message = "Usted ha ingresado correctamente";
                    if(isset($_POST['email']) && isset($_POST['clave'])){
                        $response =  Users::Login($_POST['email'],$_POST['clave']);
                        if(!$response){
                            $message =  "Combinacion Mail/Contraseña Incorrectos";
                        }else{  
                            echo $response;
                        }       
                    }else{
                        $message = "Debe cargar Mail y Password para ingresar";
                    }
                    echo PHP_EOL.$message;
                break;
                case '/materia':
                    $cabecera = getallheaders();
                    $response = "Debe ingresar un token valido";
                    if(!is_null(JWT::decode($cabecera['token'],'pro3-parcial',array('HS256')))){
                        $response = "Debe ingresar tanto nombre como cuatrimestre para crear la materia";
                        if(isset($_POST['nombre']) && isset($_POST['cuatrimestre'])){
                            $materiaNueva = new Materia($_POST['nombre'],$_POST['cuatrimestre']);
                            Data::SaveSerialized('materias.txt',$materiaNueva);                        
                            $response = "Materia Cargada correctamente";
                        }
                    }

                    echo $response;
                break;
                case '/profesor':
                    $cabecera = getallheaders();
                    $response = "Debe ingresar un token valido";
                    if(!is_null(JWT::decode($cabecera['token'],'pro3-parcial',array('HS256')))){
                        $response = "Debe completar todos los campos";
                        if(isset($_POST['nombre'])&&isset($_POST['legajo'])&&isset($_FILES['imagen'])){
                            if(Profesores::CargarProfesor($_POST['nombre'],$_POST['legajo'],$_FILES['imagen'])){
                                $response = "Profesor cargado correctamente";
                            }else{
                                $response = "Legajo Duplicado";
                            }

                        }
                    }
                    echo $response;
                break;
                case'/asignacion':
                    $cabecera = getallheaders();
                    $response = "Debe ingresar un token valido";
                    if(!is_null(JWT::decode($cabecera['token'],'pro3-parcial',array('HS256')))){
                        $response = "No se pudo cargar la asignacion";
                        if(isset($_POST['legajo']) && isset($_POST['id']) &&isset($_POST['turno'])){
                            if(Asignacion::CrearAsignacion($_POST['legajo'],$_POST['id'],$_POST['turno'])){
                                $response = "Asignacion creada correctamente";
                            }else{
                                $response = "Legajo o Materia no encontrada";
                            }
                        }
                    }
                    echo $response;
                break;
            }   
    break;
    case 'GET': 
    switch ($pathInfo) {
        case '/materia':
            $cabecera = getallheaders();
            $response = "Debe ingresar un token valido";
            if(!is_null(JWT::decode($cabecera['token'],'pro3-parcial',array('HS256')))){
                echo Materia::ListarMaterias();
            }
        break;
        case '/profesor': 
            $cabecera = getallheaders();
            $response = "Debe ingresar un token valido";
            if(!is_null(JWT::decode($cabecera['token'],'pro3-parcial',array('HS256')))){
                echo Profesores::ListarProfesores();
            }
        break;
        case '/asignacion':
            $cabecera = getallheaders();
            $response = "Debe ingresar un token valido";
            if(!is_null(JWT::decode($cabecera['token'],'pro3-parcial',array('HS256')))){
                echo Asignacion::MostrarAsignacion();
            }
        break;
    }       

    break;

}
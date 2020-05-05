<?php


require_once __DIR__.'./../vendor/autoload.php';
require_once __DIR__.'/Datos.php';

use \Firebase\JWT\JWT;


class Users{ 

   
    public $password;
    public $email;


    public function __construct($password,$email) //Uses in SignIn
    {
        $this->password = $password;
        $this->email = $email;

    }

    public static function SigIn($clave, $email){
        $response = false;
        $lstUsuarios = Data::LoadSerialized('users.txt');
        if(!$lstUsuarios){
            $response = true;
        }else{
            $response = true;
            foreach($lstUsuarios as $usuario){
                if(Users::ValidateExistingUser($usuario->email,$usuario->password,$email,$clave)){
                    $response = false;
                    break;  
                }
            }
        }
        if($response){
            $newUser = new Users($clave,$email);
            if(Data::SaveSerialized('users.txt',$newUser)){
                $response = "Usuario Creado Correctamente";
            }
        }else{
            $response = "Combinacion ya registrada en el sistema";
        }

        return $response;
    }

    private static function ValidateExistingUser($emailRegistrado,$passRegistrada,$emailEntrante,$passEntrante){
        $response = false;
        if($emailRegistrado == $emailEntrante && $passRegistrada == $passEntrante){
            $response = true;
        }
        return $response;
    }

    public static function Login($email,$clave){
        $response = Data::LoadSerialized('users.txt');
        $flag = false;
        if($response){
            $key = 'pro3-parcial';
            foreach ($response as $usuario) {
               if(Users::ValidateExistingUser($usuario->email,$usuario->password,$email,$clave)){
                $payload = array(
                    "clave" => $clave,
                    "email" => $email
                );
                $flag=true;
                break;
                }                
            }
            if($flag){
                $flag= JWT::encode($payload,$key);
            }
        }
        return $flag; 
    }

    public static function MostrarUser($token){
        $response = false;
        try{
            $decoded = JWT::decode($token,"Usuario Registrado", array("HS256"));
            $response = "Nombre: ".$decoded->nombre .PHP_EOL
                        ."Apellido: ".$decoded->apellido .PHP_EOL
                        ."Usuario: ".$decoded->email .PHP_EOL
                        ."Password: ".$decoded->clave .PHP_EOL
                        ."Telefono: ".$decoded->telefono .PHP_EOL
                        ."Privilegios: ";
            if($decoded->tipo == "true"){
                $response = $response."Usuario";
            }else{
                $response = $response."Administrador";
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }
        return $response;
    }

    public static function MostrarUsuarios($token){
        $users = JWT::decode($token,"Usuario Registrado", array("HS256"));
        $response = "";
        $lista = Data::LoadSerialized('usuarios.txt');
        
        if($users){
            if($users->tipo == "true"){
                foreach($lista as $user){
                    if($user->isUser == "true"){
                        $response = $response.$user->name.PHP_EOL;
                    }
                }
            }else{
                foreach($lista as $admin){
                    $response = $response.$admin->name.PHP_EOL;
                }
            }

        }
        return $response;      

    }

    public static function isUser($token){
        $decoded = JWT::decode($token,"Usuario Registrado",array('HS256'));
        $retorno = false;
        if(!is_null($decoded)){
            if($decoded->tipo == 'Usuario'){
                $retorno = true;
            }
        }
        return $retorno;
    }
}
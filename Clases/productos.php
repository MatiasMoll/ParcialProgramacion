<?php
require_once __DIR__.'./Datos.php';

class Producto{

    static $IdPrivado = 0;
    public $Id;
    public $producto;
    public $marca;
    public $precio;
    public $stock;
    //public $foto;

    public function  __construct($pro,$mar,$pre,$sto,$fo){
        $this->Id = Producto::$IdPrivado + 1;
        $this->producto = $pro;
        $this->marca = $mar;
        $this->precio = $pre;
        $this->stock = $sto;
        $this->foto = $fo;
        Producto::$IdPrivado = $this->Id;
    }

    public function Guardar(){
        $response = false;
        $nombreArchivo = time().'-'.$this->foto['name'];
        $tmp_name = $this->foto['tmp_name'];
        $folder= 'imagenes/';
        if(Data::SaveJson('productos.json',$this)){
            move_uploaded_file($tmp_name,$folder.$nombreArchivo);
            $response = true;
        }
        return $response;
    }
    
    public static function ListarProductos($file){
        $rta = Data::LoadJson($file);
        $response = '';
        if(!$rta){
            $response = 'Error al cargar el archivo/Archivo Vacio';
        }else{
            $response = $rta;
        }        
        return $response;
    }
}
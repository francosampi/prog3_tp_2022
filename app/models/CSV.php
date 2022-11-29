<?php

require_once './models/Factura.php';

class CSV
{
    public static function crearCSVDeDatosTabla()
    {
        $facturas = Factura::obtenerTodos();
        $ruta = "./archivos/facturas.csv";
        
        $file = fopen($ruta, "w+");
        foreach($facturas as $factura)
        {
            if($file)
                fwrite($file, implode(",", (array)$factura).PHP_EOL);                        
        }
        fclose($file);  

        return filesize($ruta)>0 ? true : false;
    }
}

?>

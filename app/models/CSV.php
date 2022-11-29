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

    public static function cargarTablaConDatosDeUnCSV($archivo)
    {
        $ret=false;

        if(file_exists($archivo))
        {
            Factura::borrarTodasLasFacturas();
            $archivo = fopen($archivo, "r");

            while(!feof($archivo))
            {
                $datoFactura = fgets($archivo);       
                                
                if(!empty($datoFactura))
                {         
                    $factura=new Factura();

                    $arr=explode(",", $datoFactura);
                    $factura->nroOrden=$arr[1];
                    $factura->idMesa=$arr[2];
                    $factura->codigoMesa=$arr[3];
                    $factura->precioTotal=$arr[4];
                    $factura->fechaAlta=$arr[5];
                    $ret=$factura->crearFactura();
                }
            }

            fclose($archivo);
            return $ret;
        }
    }
}

?>

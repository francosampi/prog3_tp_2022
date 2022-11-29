<?php
require_once './models/Factura.php';

class FacturaController extends Factura
{
    public function TraerUno($request, $response, $args)
    {
        $nroOrden = $args['nroOrden'];
        $codigoMesa = $args['codigoMesa'];

        $factura = Factura::obtenerFactura($nroOrden, $codigoMesa);
        $payload = json_encode($factura);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Factura::obtenerTodos();
        $payload = json_encode(array("facturas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id = $parametros['id'];
        Factura::borrarUna($id);

        $payload = json_encode(array("mensaje" => "Factura borrada con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
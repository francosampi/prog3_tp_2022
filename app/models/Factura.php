<?php

class Factura
{
    public $id;
    public $nroOrden;
    public $codigoMesa;
    public $precioTotal;

    public function crearFactura()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO facturas (nroOrden, codigoMesa, precioTotal, fechaAlta)
                                                       VALUES (:nroOrden, :codigoMesa, :precioTotal, :fechaAlta)");

        $consulta->bindValue(':nroOrden', $this->nroOrden, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':precioTotal', $this->precioTotal, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', date('Y-m-d h:i:s'));
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM facturas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Factura');
    }

    public static function obtenerFactura($nroOrden, $codigoMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT fr.precioTotal, fr.fechaAlta FROM facturas fr
                                                       WHERE nroOrden = :nroOrden AND codigoMesa = :codigoMesa");
        $consulta->bindValue(':nroOrden', $nroOrden, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Factura');
    }
}
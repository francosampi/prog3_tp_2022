<?php

class Producto
{
    public $id;
    public $nroOrden;
    public $nombre;
    public $sector;
    public $precio;
    public $estado; //pendiente/en preparacion/listo para servir/entregado
    public $idEmpleado;
    public $horaInicio;
    public $horaEstimada;
    public $horaFinalizacion;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nroOrden, nombre, sector, precio, estado, horaInicio)
                                                       VALUES (:nroOrden, :nombre, :sector, :precio, :estado, :horaInicio)");

        $consulta->bindValue(':nroOrden', $this->nroOrden, PDO::PARAM_INT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':estado', "Pendiente", PDO::PARAM_STR);
        $consulta->bindValue(':horaInicio', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProductoPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }

    public static function obtenerProductosPorNroOrden($nroOrden)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE nroOrden = :nroOrden");
        $consulta->bindValue(':nroOrden', $nroOrden, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProductosPorSector($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE sector = :sector");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function actualizarEstadoPreparacion($id, $idEmpleado, $horaEstimada)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productos
                                                       SET idEmpleado = :idEmpleado, horaEstimada = :horaEstimada, estado = :estado WHERE id = :id");
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':horaEstimada', $horaEstimada, PDO::PARAM_STR);
        $consulta->bindValue(':estado', "En preparacion", PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerPrecioTotalPorNroOrden($nroOrden)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(p.precio) precioTotal FROM productos p WHERE nroOrden = :nroOrden");
        $consulta->bindValue(':nroOrden', $nroOrden, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject()->precioTotal;
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM `productos` WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
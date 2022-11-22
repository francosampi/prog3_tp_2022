<?php

class Pedido
{
    public $id;
    public $idMesa;
    public $idMozo;
    public $nombreCliente;
    public $fechaAlta;
    public $precioTotal;
    public $pathFoto;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (idMesa, nombreCliente, fechaAlta, pathFoto)
                                                       VALUES (:idMesa, :nombreCliente, :fechaAlta, :pathFoto)");

        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', date("Y-m-d H:i:s"));
        $consulta->bindValue(':pathFoto', $this->pathFoto);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPedidoPorCodigoMesa($codigoMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE id = :id");
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPedidosSinMozo()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE idMozo = NULL");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerTiempoEstimadoPedido($codigoMesa, $nroOrden)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.nombreCliente, p.fechaAlta,
                                                       MAX(pr.horaEstimada) horaEstimada
                                                       FROM productos pr
                                                       JOIN pedidos p
                                                       ON pr.nroOrden = :nroOrden
                                                       JOIN mesas m
                                                       ON p.idMesa = m.id
                                                       WHERE m.codigo = :codigoMesa");
        $consulta->bindValue(':nroOrden', $nroOrden, PDO::PARAM_INT);
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('stdClass');
    }

    public static function obtenerPedidosConTiempoEstimado()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.idMesa, p.idMozo, p.nombreCliente, p.fechaAlta, p.precioTotal, p.pathFoto,
                                                       MAX(pr.horaEstimada) horaEstimada
                                                       FROM productos pr
                                                       JOIN pedidos p
                                                       ON pr.nroOrden = p.id
                                                       GROUP BY p.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }
    
    public static function actualizarPrecioTotal($id, $precioTotal)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET precioTotal = :precioTotal WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':precioTotal', $precioTotal);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date('Y-m-d H:i:s'));
        $consulta->execute();
    }
}
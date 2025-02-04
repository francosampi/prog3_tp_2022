<?php

class Pedido
{
    public $id;
    public $nroOrden;
    public $idMesa;
    public $nombreCliente;
    public $fechaAlta;
    public $precioTotal;
    public $pathFoto;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (nroOrden, idMesa, nombreCliente, estado, fechaAlta, pathFoto)
                                                       VALUES (:nroOrden, :idMesa, :nombreCliente, :estado, :fechaAlta, :pathFoto)");

        $consulta->bindValue(':nroOrden', $this->nroOrden, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $this->fechaAlta, PDO::PARAM_STR);
        $consulta->bindValue(':pathFoto', $this->pathFoto, PDO::PARAM_STR);
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

    public static function obtenerPedidoPorNroOrden($nroOrden)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE nroOrden = :nroOrden");
        $consulta->bindValue(':nroOrden', $nroOrden, PDO::PARAM_STR);
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

    public static function obtenerTiempoEstimadoPedido($codigoMesa, $nroOrden)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT TIMESTAMPDIFF(minute, DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'),
                                                                                    DATE_FORMAT(pr.horaEstimada, '%Y%m%d%H%i%s'))
                                                                                    AS 'tiempoEstimadoEnMinutos'
                                                                                    FROM productos pr
                                                                                    LEFT JOIN pedidos p
                                                                                    ON pr.nroOrden = :nroOrden
                                                                                    LEFT JOIN mesas m
                                                                                    ON p.idMesa = m.id
                                                                                    WHERE m.codigo = :codigoMesa");
                                                      
        $consulta->bindValue(':nroOrden', $nroOrden, PDO::PARAM_STR);
        $consulta->bindValue(':codigoMesa', $codigoMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('stdClass');
    }

    /*
    $consulta = $objAccesoDatos->prepararConsulta("SELECT p.nombreCliente, p.fechaAlta,
                                                    MAX(pr.horaEstimada) horaEstimada
                                                    FROM productos pr
                                                    LEFT JOIN pedidos p
                                                    ON pr.nroOrden = :nroOrden
                                                    LEFT JOIN mesas m
                                                    ON p.idMesa = m.id
                                                    WHERE m.codigo = :codigoMesa");
                                                    */

    public static function obtenerPedidosConTiempoEstimado()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.nroOrden, p.idMesa, p.nombreCliente, p.fechaAlta, p.precioTotal, p.pathFoto,
                                                       MAX(pr.horaEstimada) horaEstimada
                                                       FROM productos pr
                                                       JOIN pedidos p
                                                       ON pr.nroOrden = p.nroOrden
                                                       GROUP BY p.id");

        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }

    public static function obtenerPedidosListos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE estado = 'Listo para servir'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }

    public static function actualizarPedido($pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado, precioTotal = :precioTotal
                                                      WHERE id = :id");

        $consulta->bindValue(':estado', $pedido->estado);
        $consulta->bindValue(':precioTotal', $pedido->precioTotal);
        $consulta->bindValue(':id', $pedido->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
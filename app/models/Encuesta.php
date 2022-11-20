<?php

class Encuesta{
    public $id;
    public $codigoMesa;
    public $cliente;
    public $puntajeMesa;
    public $puntajeResto;
    public $puntajeMozo;
    public $puntajeCocinero;
    public $puntajePromedio;
    public $descripcion; //66 ch.

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (codigoMesa, cliente, puntajeMesa, puntajeResto, puntajeMozo, puntajeCocinero, puntajePromedio, descripcion)
                                                       VALUES (:codigoMesa, :cliente, :puntajeMesa, :puntajeResto, :puntajeMozo, :puntajeCocinero, :puntajePromedio, :descripcion)");

        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':puntajeMesa', $this->puntajeMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeResto', $this->puntajeResto, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeMozo', $this->puntajeMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntajeCocinero', $this->puntajeCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':puntajePromedio', $this->generarPuntajePromedio(), PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public function generarPuntajePromedio()
    {
        return ($this->puntajeMesa + $this->puntajeResto + $this->puntajeMozo + $this->puntajeCocinero)/4;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerEncuestasPorPuntaje($cantidad){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta('SELECT * FROM encuestas ORDER BY puntajePromedio DESC LIMIT :cantidad');
        $consulta->bindValue(':cantidad', $cantidad);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'encuesta');
    }
}
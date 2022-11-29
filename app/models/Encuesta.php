<?php

class Encuesta
{
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuesta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas
                                                       WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function obtenerEncuestasPorPuntaje(){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta('SELECT * FROM encuestas ORDER BY puntajePromedio DESC');
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
}
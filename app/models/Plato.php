<?php

class Plato
{
    public $id;
    public $nombre;
    public $sector;
    public $precio;
    public $stock;

    public function crearPlato()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO platos (nombre, sector, precio, stock)
                                                       VALUES (:nombre, :sector, :precio, :stock)");

        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM platos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Plato');
    }

    public static function obtenerTodosCliente()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pl.nombre, pl.precio, pl.sector FROM platos pl
                                                       WHERE stock>0 ORDER BY pl.sector");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'stdClass');
    }

    public static function obtenerPlato($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM platos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Plato');
    }

    public static function actualizarPlato($plato)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE platos
                                                      SET nombre = :nombre, sector = :sector, precio = :precio, stock = :stock WHERE id = :id");
       
        $consulta->bindValue(':nombre', $plato->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $plato->sector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $plato->precio, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $plato->stock, PDO::PARAM_INT);
        $consulta->bindValue(':id', $plato->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPlato($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM platos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
<?php

class Producto
{
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $created_at;
    private $updated_at;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->descripcion = $data['descripcion'] ?? '';
        $this->precio = $data['precio'] ?? 0.0;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUpdatedAt() { return $this->updated_at; }

    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->nombre)) {
            $errors[] = 'El nombre es obligatorio';
        }

        if (empty($this->descripcion)) {
            $errors[] = 'La descripción es obligatoria';
        }

        if ($this->precio <= 0) {
            $errors[] = 'El precio debe ser mayor a 0';
        }

        return $errors;
    }
} 
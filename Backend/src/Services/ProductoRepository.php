<?php

require_once __DIR__ . '/../Database/Connection.php';
require_once __DIR__ . '/../Models/Producto.php';

class ProductoRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
    }

    public function findAll()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM productos ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->fetchAll();
            
            $productos = [];
            foreach ($result as $row) {
                $productos[] = new Producto($row);
            }
            
            return $productos;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener productos: " . $e->getMessage());
        }
    }

    public function findById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            
            if (!$result) {
                return null;
            }
            
            return new Producto($result);
        } catch (PDOException $e) {
            throw new Exception("Error al obtener producto: " . $e->getMessage());
        }
    }

    public function create(Producto $producto)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO productos (nombre, descripcion, precio) VALUES (?, ?, ?)"
            );
            $stmt->execute([
                $producto->getNombre(),
                $producto->getDescripcion(),
                $producto->getPrecio()
            ]);
            
            $producto->setId($this->db->lastInsertId());
            return $producto;
        } catch (PDOException $e) {
            throw new Exception("Error al crear producto: " . $e->getMessage());
        }
    }

    public function update($id, Producto $producto)
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?"
            );
            $result = $stmt->execute([
                $producto->getNombre(),
                $producto->getDescripcion(),
                $producto->getPrecio(),
                $id
            ]);
            
            if ($stmt->rowCount() === 0) {
                return null;
            }
            
            return $this->findById($id);
        } catch (PDOException $e) {
            throw new Exception("Error al actualizar producto: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception("Error al eliminar producto: " . $e->getMessage());
        }
    }
} 
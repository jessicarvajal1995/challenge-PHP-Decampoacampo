<?php

require_once __DIR__ . '/ProductoRepository.php';
require_once __DIR__ . '/CurrencyService.php';

class ProductoService
{
    private $repository;
    private $currencyService;

    public function __construct()
    {
        $this->repository = new ProductoRepository();
        $this->currencyService = new CurrencyService();
    }

    public function getAllProductos()
    {
        $productos = $this->repository->findAll();
        return $this->addUsdPrices($productos);
    }

    public function getProductoById($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID inválido");
        }

        $producto = $this->repository->findById($id);
        if (!$producto) {
            throw new Exception("Producto no encontrado");
        }

        return $this->addUsdPrice($producto);
    }

    public function createProducto($data)
    {
        $producto = new Producto($data);
        $errors = $producto->validate();
        
        if (!empty($errors)) {
            throw new Exception("Datos inválidos: " . implode(', ', $errors));
        }

        $producto = $this->repository->create($producto);
        return $this->addUsdPrice($producto);
    }

    public function updateProducto($id, $data)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID inválido");
        }

        $producto = new Producto($data);
        $errors = $producto->validate();
        
        if (!empty($errors)) {
            throw new Exception("Datos inválidos: " . implode(', ', $errors));
        }

        $producto = $this->repository->update($id, $producto);
        if (!$producto) {
            throw new Exception("Producto no encontrado");
        }

        return $this->addUsdPrice($producto);
    }

    public function deleteProducto($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("ID inválido");
        }

        $deleted = $this->repository->delete($id);
        if (!$deleted) {
            throw new Exception("Producto no encontrado");
        }

        return true;
    }

    private function addUsdPrices($productos)
    {
        return array_map([$this, 'addUsdPrice'], $productos);
    }

    private function addUsdPrice($producto)
    {
        $data = $producto->toArray();
        $data['precio_usd'] = $this->currencyService->convertToUsd($producto->getPrecio());
        return $data;
    }
} 
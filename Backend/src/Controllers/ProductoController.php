<?php

require_once __DIR__ . '/../Services/ProductoService.php';
require_once __DIR__ . '/../Utils/Response.php';

class ProductoController
{
    private $service;

    public function __construct()
    {
        $this->service = new ProductoService();
    }

    public function index()
    {
        try {
            $productos = $this->service->getAllProductos();
            Response::success($productos, 'Productos obtenidos correctamente');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $producto = $this->service->getProductoById($id);
            Response::success($producto, 'Producto obtenido correctamente');
        } catch (Exception $e) {
            $status = ($e->getMessage() === 'Producto no encontrado') ? 404 : 400;
            Response::error($e->getMessage(), $status);
        }
    }

    public function store()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                Response::error('Datos JSON inválidos', 400);
                return;
            }

            $producto = $this->service->createProducto($input);
            Response::success($producto, 'Producto creado correctamente');
        } catch (Exception $e) {
            Response::error($e->getMessage(), 400);
        }
    }

    public function update($id)
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                Response::error('Datos JSON inválidos', 400);
                return;
            }

            $producto = $this->service->updateProducto($id, $input);
            Response::success($producto, 'Producto actualizado correctamente');
        } catch (Exception $e) {
            $status = ($e->getMessage() === 'Producto no encontrado') ? 404 : 400;
            Response::error($e->getMessage(), $status);
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->deleteProducto($id);
            Response::success(null, 'Producto eliminado correctamente');
        } catch (Exception $e) {
            $status = ($e->getMessage() === 'Producto no encontrado') ? 404 : 400;
            Response::error($e->getMessage(), $status);
        }
    }
} 
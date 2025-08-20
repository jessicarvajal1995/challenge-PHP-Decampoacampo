<?php

class CurrencyService
{
    private $precioUsd;

    public function __construct()
    {
        $this->precioUsd = (float) (getenv('PRECIO_USD') ?: 1000.0);
    }

    public function convertToUsd($precioArs)
    {
        return round($precioArs / $this->precioUsd, 2);
    }

    public function getPrecioUsd()
    {
        return $this->precioUsd;
    }
} 
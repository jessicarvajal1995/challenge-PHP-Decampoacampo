#!/bin/bash

echo "Configurando aplicación de Gestión de Productos..."

# Verifico si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "Docker no está instalado. Por favor instala Docker primero."
    exit 1
fi

# Verifico si Docker Compose está instalado
if ! command -v docker compose &> /dev/null; then
    echo "Docker Compose no está instalado. Por favor instala Docker Compose primero."
    exit 1
fi

# Copio archivo de entorno si no existe
if [ ! -f "Backend/.env" ]; then
    echo "Creando archivo de configuración..."
    cp Backend/.env.example Backend/.env
    echo "Archivo .env creado con configuración por defecto"
else
    echo "Archivo .env ya existe"
fi

# Verifico puertos disponibles
echo "Verificando puertos disponibles..."

if lsof -Pi :3000 -sTCP:LISTEN -t >/dev/null; then
    echo "Puerto 3000 está ocupado. El frontend se ejecutará en otro puerto."
fi

if lsof -Pi :8080 -sTCP:LISTEN -t >/dev/null; then
    echo "Puerto 8080 está ocupado. El backend se ejecutará en otro puerto."
fi

if lsof -Pi :3306 -sTCP:LISTEN -t >/dev/null; then
    echo "Puerto 3306 está ocupado. MySQL se ejecutará en otro puerto."
fi

# Construyo y levanto contenedores
echo "Construyendo y levantando contenedores..."
docker compose up -d --build

# Espero a que los servicios estén listos
echo "Esperando a que los servicios estén listos..."
sleep 15

# Verifico que los servicios estén corriendo
echo "Verificando servicios..."

if curl -s http://localhost:8080/health > /dev/null; then
    echo "Backend funcionando correctamente"
else
    echo "Backend no responde"
fi

if curl -s http://localhost:3000 > /dev/null; then
    echo "Frontend funcionando correctamente"
else
    echo "Frontend no responde"
fi

echo ""
echo "🎉 ¡Configuración completada!"
echo ""
echo "📱 Accesos a la aplicación:"
echo "   Frontend: http://localhost:3000"
echo "   Backend API: http://localhost:8080"
echo "   Health Check: http://localhost:8080/health"
echo ""
echo "📊 Para ver los logs:"
echo "   docker compose logs -f"
echo ""
echo "🛑 Para detener la aplicación:"
echo "   docker compose down" 
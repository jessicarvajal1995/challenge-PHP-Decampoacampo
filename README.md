# Gestión de Productos - Challenge PHP - Decampoacampo - Jessika Carvajal

Aplicación completa para la gestión de un catálogo de productos con API REST en PHP puro y frontend en HTML/CSS/JavaScript.

## 🚀 Características

### Backend (API REST)
- **Arquitectura MVC + Clean Architecture** con PHP puro sin usar ningun framework
- **Operaciones CRUD** completas para productos
- **Conversión automática de precios** de ARS a USD usando variable de entorno
- **Patrón Singleton** para conexión a base de datos
- **CORS habilitado** para comunicación con frontend
- **Manejo de errores** y validaciones
- **Respuestas JSON estandarizadas**

### Frontend
- **Interfaz web sencilla** con HTML, CSS y JavaScript puro
- **Diseño responsivo** y amigable
- **Operaciones CRUD** en tiempo real
- **Validación de formularios** del lado cliente
- **Notificaciones visuales** de éxito/error
- **Conversión de precios automática** (ARS/USD)

### Base de Datos
- **MySQL 8.0** con tabla de productos optimizada
- **Datos de ejemplo** agregue datos de prueba

### Infraestructura
- **Contenedores Docker** para fácil despliegue
- **Docker Compose** para orquestación completa
- **Configuración de red** aislada

## 📋 Requerimientos

- Docker y Docker Compose instalados
- Puertos 3000, 8080 y 3306 disponibles

## 🛠️ Instalación y Configuración

### 1. Clonar el repositorio
```bash
git clone https://github.com/jessicarvajal1995/challenge-PHP-Decampoacampo/
cd challenge-PHP-Decampoacampo
```

### 2. Configurar variables de entorno
```bash
# El archivo .env ya está configurado con valores por defecto, tambien deje un .env.example con la información
PRECIO_USD=1308.08  # Valor actual del dólar en pesos argentinos
```

### 3. Inicio rápido con script automatizado (Recomendado)
```bash
# Ejecutar el script de configuración automática
chmod +x scripts/setup.sh
./scripts/setup.sh
```

El script automáticamente:
- ✅ Verifica que Docker y Docker Compose estén instalados
- ✅ Crea el archivo `.env` si no existe
- ✅ Verifica disponibilidad de puertos (3000, 8080, 3306)
- ✅ Construye y levanta todos los contenedores
- ✅ Espera a que los servicios estén listos
- ✅ Verifica que backend y frontend respondan correctamente
- ✅ Muestra información de acceso a la aplicación

### 4. Alternativa: Levantar manualmente con Docker
```bash
# Construir y levantar todos los contenedores
docker compose up -d --build

```

### 5. Verificar que todo esté funcionando
- **Frontend**: http://localhost:3000
- **API**: http://localhost:8080
- **Health Check**: http://localhost:8080/health
- **Base de datos**: localhost:3306 (usuario: root, password: rootpassword)

## 🔧 Uso de la API

### Endpoints disponibles

Dejo la collection para uso por postman de la API

Los archivos se encuentran en el path: **challenge-PHP-Decampoacampo/postman**

## 🖥️ Uso del Frontend

1. En el navegador: http://localhost:3000
2. **Agregar productos**: Usa el formulario en la parte superior
3. **Ver productos**: Los productos se muestran en la tabla con precios en ARS y USD
4. **Editar productos**: Haz clic en "Editar" en cualquier producto
5. **Eliminar productos**: Haz clic en "Eliminar" y confirma la acción

### Funcionalidades del Frontend
- **Listado dinámico** de productos con actualización automática
- **Formulario de creación/edición** con validaciones
- **Confirmación de eliminación** con modal
- **Notificaciones** visuales para todas las operaciones
- **Formateo automático** de números y fechas
- **Diseño responsivo** 

## 🏗️ Estructura del proyecto

```
/
├── Backend/                 # API REST en PHP
│   ├── src/
│   │   ├── Controllers/     # Controladores MVC
│   │   ├── Models/          # Modelos de datos
│   │   ├── Services/        # Servicios de negocio
│   │   ├── Database/        # Conexión y configuración DB
│   │   └── Utils/           # Utilidades (Router, Response)
│   ├── config/
│   │   └── env_loader.php   # Cargador de variables de entorno
│   ├── public/
│   │   ├── index.php        # Punto de entrada
│   │   └── .htaccess        # Configuración Apache
│   ├── Dockerfile           # Imagen Docker PHP/Apache
│   ├── .env                 # Variables de entorno
│   └── .env.example         # Ejemplo de configuración
├── Frontend/                # Aplicación web
│   ├── css/styles.css       # Estilos CSS
│   ├── js/app.js           # Lógica JavaScript
│   ├── index.html          # Página principal
│   ├── Dockerfile          # Imagen Docker Nginx
│   └── nginx.conf          # Configuración Nginx
├── database/
│   └── init.sql            # Script de inicialización DB
├── postman/                # Collection de Postman
├── scripts/                # Scripts de automatización
│   └── setup.sh            # Script de configuración automática
├── docker-compose.yml      # Orquestación de contenedores
└── README.md              
```







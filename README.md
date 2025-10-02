<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Veltro

Una plataforma web basada en Laravel para jugadores de fútbol amateur. Veltro permite a los jugadores encontrar otros para jugar, crear sus propios equipos, enfrentarse a otros equipos, gestionar sus equipos, crear sus perfiles y mucho más.

## Integrantes

-   Paolo Fumero
-   Nahuel Galego
-   Martin Landaco
-   Mateo López
-   Fermín Martínez

## Prerrequisitos

Antes de comenzar, asegúrate de tener instalado en tu sistema:

-   **PHP 8.4+** - Lenguaje de programación
-   **Composer** - Gestor de dependencias de PHP
-   **Node.js 18+** y **bun** - Para compilación de assets frontend
-   **MySQL 8.0+** - Servidor de base de datos
-   **Git** - Control de versiones

## Configuración de Desarrollo Local

### 1. Clonar el Repositorio

```bash
git clone git@github.com:mrnzdev/veltro.git
cd veltro
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

### 3. Instalar Dependencias de Node.js

```bash
bun install
```

### 4. Configuración del Entorno

Copia el archivo de entorno y configura tus ajustes locales:

```bash
cp .env.example .env
```

Edita el archivo `.env` con tu configuración local de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=veltro
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 6. Configuración de la Base de Datos

Crea la base de datos y ejecuta las migraciones:

```bash
# Crear la base de datos (si no existe)
mysql -u tu_usuario -p -e "CREATE DATABASE veltro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Ejecutar migraciones
php artisan migrate

# (Opcional) Poblar la base de datos con datos de ejemplo
php artisan db:seed
```

### 7. Compilar Assets Frontend

```bash
# Compilación de desarrollo (con recarga automática)
bun run dev

# O para compilación de producción
bun run build
```

### 8. Iniciar el Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## Estructura del Proyecto

```
veltro/
├── app/                          # Código principal de la aplicación
│   ├── Http/
│   │   ├── Controllers/          # Controladores de peticiones HTTP
│   │   └── Middleware/           # Middleware HTTP
│   ├── Models/                   # Modelos Eloquent ORM
│   └── Providers/                # Proveedores de servicios
├── bootstrap/                    # Archivos de arranque de la aplicación
│   └── cache/                    # Archivos de caché del framework
├── config/                       # Archivos de configuración
├── database/
│   ├── factories/                # Factories de modelos para testing
│   ├── migrations/               # Migraciones de base de datos
│   └── seeders/                  # Seeders de base de datos
├── public/                       # Directorio raíz del servidor web
│   └── build/                    # Assets compilados
│       └── assets/
├── resources/
│   ├── css/                      # Archivos CSS fuente
│   ├── js/                       # Archivos JavaScript fuente
│   └── views/                    # Archivos de plantillas Blade
│       ├── auth/                 # Vistas de autenticación
│       └── profile/              # Vistas de perfil
├── routes/                       # Definiciones de rutas
├── storage/                      # Almacenamiento de la aplicación
│   ├── app/
│   │   ├── private/              # Almacenamiento privado
│   │   └── public/               # Almacenamiento público
│   ├── framework/
│   │   ├── cache/                # Caché del framework
│   │   │   └── data/
│   │   ├── sessions/             # Archivos de sesión
│   │   ├── testing/              # Archivos de testing
│   │   └── views/                # Vistas compiladas
│   └── logs/                     # Logs de la aplicación
├── tests/                        # Archivos de pruebas
│   ├── Feature/                  # Pruebas de características
│   └── Unit/                     # Pruebas unitarias
├── vendor/                       # Dependencias de Composer
├── .env                          # Configuración del entorno
├── .env.example                  # Plantilla del entorno
├── composer.json                 # Dependencias de PHP
├── package.json                  # Dependencias de Node.js
├── vite.config.js               # Configuración de Vite
└── README.md                    # Este archivo
```

## Comandos Disponibles

### Desarrollo

```bash
# Iniciar servidor de desarrollo
php artisan serve

# Observar cambios de archivos y recompilar assets
bun run dev

# Compilar assets para producción
bun run build
```

### Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir migraciones
php artisan migrate:rollback

# Refrescar migraciones (revertir + migrar)
php artisan migrate:refresh

# Poblar base de datos
php artisan db:seed

# Crear una nueva migración
php artisan make:migration create_table_name
```

## Gestión de Paquetes

Este proyecto utiliza **bun** como gestor de paquetes de Node.js para mejor rendimiento y eficiencia de espacio en disco.

### Instalación de Dependencias

```bash
# Instalar todas las dependencias
bun install

# Agregar una nueva dependencia
bun add nombre-paquete

# Agregar una dependencia de desarrollo
bun add -D nombre-paquete

# Remover una dependencia
bun remove nombre-paquete
```

### Configuración de Conexión

Actualiza tu archivo `.env` con las credenciales correctas de la base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=veltro
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

# Documentación de CaesarTalk

## Descripción del Proyecto

**CaesarTalk** es un sistema de mensajes encriptados diseñado para permitir a los usuarios registrados enviarse mensajes de forma segura.

La característica principal de la aplicación es que los mensajes son encriptados utilizando un **Cifrado César** (desplazamiento de caracteres) definido por el usuario antes de ser enviados al servidor. Posteriormente, estos mensajes son desencriptados para su lectura por el destinatario autorizado.

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalado el siguiente software en tu sistema:

-   **PHP** (versión 8.2 o superior)
-   **Composer** (Gestor de dependencias de PHP)
-   **Node.js** y **NPM** (Gestor de paquetes de Node)
-   **Git** (Opcional, para clonar el repositorio)

## Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

### 1. Clonar el Repositorio

Si tienes acceso al repositorio git, clónalo en tu máquina local:

```bash
git clone <URL_DEL_REPOSITORIO>
cd caesarTalk
```

### 2. Instalar Dependencias de Backend (PHP)

Utiliza Composer para instalar las dependencias necesarias de Laravel:

```bash
composer install
```

### 3. Instalar Dependencias de Frontend (Node.js)

Utiliza NPM para instalar las bibliotecas de JavaScript y CSS (Tailwind, Vite, Alpine.js, etc.):

```bash
npm install
```

### 4. Configuración del Entorno

Duplica el archivo de configuración de ejemplo `.env.example` y renómbralo a `.env`:

```bash
cp .env.example .env
# En Windows (PowerShell): copy .env.example .env
```

Abre el archivo `.env` y configura tu conexión a la base de datos si es necesario (por defecto Laravel puede usar SQLite).

### 5. Generar Clave de Aplicación

Genera la clave única para tu aplicación Laravel:

```bash
php artisan key:generate
```

### 6. Migrar la Base de Datos

Ejecuta las migraciones para crear las tablas necesarias en la base de datos:

```bash
php artisan migrate
```

## Comandos de Terminal

Para ejecutar la aplicación en un entorno de desarrollo, necesitarás dos terminales corriendo simultáneamente (o usar el comando compuesto).

### Opción 1: Ejecución Manual (Dos Terminales)

**Terminal 1: Servidor Laravel**
Inicia el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

Esto servirá la aplicación usualmente en `http://127.0.0.1:8000`.

**Terminal 2: Servidor Vite (Frontend)**
Inicia el servidor de desarrollo de Vite para la recarga en caliente de estilos y scripts:

```bash
npm run dev
```

### Opción 2: Ejecución con Composer (Una Terminal)

El proyecto está configurado para ejecutar ambos servidores simultáneamente con un solo comando (si `concurrently` está configurado correctamente en los scripts de composer):

```bash
composer run dev
```

## Tecnologías Utilizadas

-   **Backend:** Laravel 11 (PHP)
-   **Frontend:**
    -   Vite (Build tool)
    -   TailwindCSS (Estilos)
    -   Alpine.js (Interactividad ligera)
    -   Flowbite (Componentes UI)
    -   SweetAlert2 (Alertas modales)
-   **Base de Datos:** Compatible con MySQL, PostgreSQL, SQLite, etc.

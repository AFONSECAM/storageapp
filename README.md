# StorageApp - Sistema de Gestión de Archivos

Sistema web de almacenamiento de archivos con gestión de usuarios, cuotas y panel administrativo desarrollado en Laravel 11.

## 🏗️ Decisiones de Diseño

### Arquitectura
- **Separación de rutas**: `web.php` para páginas HTML y `api.php` para endpoints AJAX/JSON
- **Services Pattern**: Lógica de negocio separada en `FileValidationService` y `FileStorageService`
- **Middleware personalizado**: `CheckRole` para autorización basada en roles
- **Modales vs Páginas**: Administración mediante modales para mejor UX

### Base de Datos
- **Jerarquía de cuotas**: Individual → Grupo → Global (configuración flexible)
- **Soft deletes**: No implementado para simplicidad, eliminación directa
- **Relaciones**: User-Group (belongsTo), User-Files (hasMany)

### Frontend
- **Bootstrap 5**: Framework CSS moderno y responsive
- **SweetAlert2**: Alertas elegantes y confirmaciones
- **Vanilla JS**: Sin frameworks pesados, JavaScript puro optimizado
- **AJAX**: Operaciones sin recarga de página

### Seguridad
- **Validación de extensiones**: Lista configurable de tipos prohibidos
- **Validación ZIP**: Escaneo de contenido interno de archivos comprimidos
- **CSRF Protection**: Tokens en todas las operaciones
- **Middleware de roles**: Protección de rutas administrativas

## 📋 Requisitos del Sistema

- PHP 8.2+
- Composer
- SQLite/MySQL/PostgreSQL
- Extensión PHP ZipArchive (para validación de ZIP)

## 🚀 Instalación y Configuración

### 1. Clonar el Repositorio
```bash
git clone https://github.com/AFONSECAM/storageapp.git
cd storage-app
```

### 2. Instalar Dependencias
```bash
composer install
```

### 3. Configurar Variables de Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos
Editar `.env` con la configuración de base de datos:

**Para MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=storageapp
DB_USERNAME=usuario_bd
DB_PASSWORD=password_bd
```

### 5. Crear Base de Datos (MySQL)
storageapp

### 6. Ejecutar Migraciones y Seeders
```bash
# Migrar tablas
php artisan migrate

# Ejecutar seeders (crea usuarios de ejemplo y configuración)
php artisan db:seed
```

### 7. Configurar Almacenamiento
```bash
# Crear enlace simbólico para archivos públicos
php artisan storage:link
```

### 8. Iniciar Servidor de Desarrollo
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

## 👥 Credenciales de Ejemplo

### Administrador
- **Email**: `admin@storageapp.com`
- **Contraseña**: `123456789`
- **Permisos**: Acceso completo al panel administrativo

### Usuario Regular
- **Email**: `user@storageapp.com`
- **Contraseña**: `123456`
- **Permisos**: Solo gestión de archivos personales

## 🎯 Funcionalidades Principales

### Para Usuarios
- ✅ Registro y autenticación
- ✅ Subida de archivos con barra de progreso
- ✅ Validación de tipos de archivo
- ✅ Gestión de archivos personales (eliminación)
- ✅ Control de cuota de almacenamiento

### Para Administradores
- ✅ Panel de administración completo
- ✅ Gestión de usuarios (crear, editar, eliminar)
- ✅ Gestión de grupos con cuotas
- ✅ Configuración global del sistema
- ✅ Control de extensiones prohibidas

## ⚙️ Configuración del Sistema

### Cuotas de Almacenamiento
El sistema maneja cuotas en el siguiente orden de prioridad:
1. **Cuota individual** del usuario
2. **Cuota del grupo** al que pertenece
3. **Cuota global** del sistema (por defecto: 10MB)

### Extensiones Prohibidas
Por defecto se prohíben: `exe, bat, js, php, sh`

Configurable desde el panel de administración.

### Validación de Archivos ZIP
El sistema escanea automáticamente el contenido de archivos ZIP para verificar que no contengan tipos de archivo prohibidos.

## 📁 Estructura del Proyecto

```
storage-app/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Controllers administrativos
│   │   ├── AuthController.php
│   │   └── FileController.php
│   ├── Models/              # Modelos Eloquent
│   ├── Services/            # Lógica de negocio
│   └── Http/Middleware/     # Middleware personalizado para rol
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/            # Datos de ejemplo
├── public/js/              # JavaScript frontend
├── resources/views/        # Plantillas Blade
    ├── components/         # Componentes Blade
└── routes/
    ├── web.php             # Rutas HTML
    └── api.php             # Rutas AJAX/JSON
```

## 🔧 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Recrear base de datos
php artisan migrate:fresh --seed

# Ver rutas disponibles
php artisan route:list
```

## 🐛 Solución de Problemas

### Archivos no se suben
Verificar configuración PHP:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
extension=zip
```

## 📝 Notas Técnicas

- Los archivos se almacenan en `storage/app/public/uploads/{user_id}/`
- Las validaciones se ejecutan tanto en frontend como backend
- El sistema usa sesiones web para autenticación (no tokens API)
- Compatible con PHP 8.2+ y Laravel 11
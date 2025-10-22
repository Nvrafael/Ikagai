# 🛠️ Comandos Artisan Personalizados

## 📋 Comandos Disponibles

Se han creado comandos personalizados para facilitar la gestión del sistema Ikagai.

---

## 👥 Gestión de Usuarios

### 1. Listar todos los usuarios
```bash
php artisan users:list
```

**Descripción:** Muestra una tabla con todos los usuarios del sistema, incluyendo ID, nombre, email, rol y fecha de creación.

**Ejemplo de salida:**
```
📊 Total de usuarios: 3

┌────┬──────────────────┬─────────────────────────┬───────────────────┬──────────────────┐
│ ID │ Nombre           │ Email                   │ Rol               │ Creado           │
├────┼──────────────────┼─────────────────────────┼───────────────────┼──────────────────┤
│ 1  │ Admin User       │ admin@ikagai.com        │ 🔧 Admin          │ 21/10/2025 09:28 │
│ 2  │ Dr. Maria García │ nutritionist@ikagai.com │ 👨‍⚕️ Nutricionista │ 21/10/2025 09:28 │
│ 3  │ Juan Pérez       │ client@ikagai.com       │ 👤 Cliente        │ 21/10/2025 09:28 │
└────┴──────────────────┴─────────────────────────┴───────────────────┴──────────────────┘
```

---

### 2. Probar login de un usuario
```bash
php artisan users:test-login {email} {password}
```

**Descripción:** Verifica si las credenciales de un usuario son correctas sin necesidad de usar el navegador.

**Ejemplos:**
```bash
php artisan users:test-login admin@ikagai.com password
php artisan users:test-login nutritionist@ikagai.com password
php artisan users:test-login client@ikagai.com password
```

**Ejemplo de salida exitosa:**
```
🔍 Buscando usuario: admin@ikagai.com
✅ Usuario encontrado:
   • ID: 1
   • Nombre: Admin User
   • Email: admin@ikagai.com
   • Rol: admin

✅ ¡Contraseña correcta!
🎉 El login debería funcionar correctamente.
```

---

### 3. Resetear contraseña de un usuario
```bash
php artisan users:reset-password {email} [--password=nueva_contraseña]
```

**Descripción:** Permite cambiar la contraseña de un usuario. Por defecto usa "password".

**Ejemplos:**
```bash
# Resetear a "password" (por defecto)
php artisan users:reset-password admin@ikagai.com

# Resetear a una contraseña personalizada
php artisan users:reset-password admin@ikagai.com --password=miNuevaPass123

# Sin confirmación (útil para scripts)
php artisan users:reset-password admin@ikagai.com --no-interaction
```

**Ejemplo de salida:**
```
🔍 Usuario encontrado:
   • Nombre: Admin User
   • Email: admin@ikagai.com
   • Rol: admin

✅ Contraseña reseteada exitosamente!

🔐 Nuevas credenciales:
   Email:    admin@ikagai.com
   Password: password

✅ Contraseña verificada - El login debería funcionar ahora.
```

---

## 🔍 Verificación del Sistema

### 4. Verificar sistema completo
```bash
php artisan system:verify
```

**Descripción:** Realiza una verificación completa del sistema, incluyendo:
- Conexión a base de datos
- Usuarios de prueba y sus contraseñas
- Rutas de autenticación
- Tablas de base de datos
- Archivos de configuración

**Ejemplo de salida:**
```
🔍 VERIFICACIÓN DEL SISTEMA IKAGAI

1️⃣  Verificando conexión a base de datos...
   ✅ Conectado a: Ikagai

2️⃣  Verificando usuarios de prueba...
   ✅ admin@ikagai.com (admin)
      🔑 Contraseña verificada
   ✅ nutritionist@ikagai.com (nutritionist)
      🔑 Contraseña verificada
   ✅ client@ikagai.com (client)
      🔑 Contraseña verificada

3️⃣  Verificando rutas de autenticación...
   ✅ Ruta 'login' registrada
   ✅ Ruta 'logout' registrada
   ✅ Ruta 'dashboard' registrada

4️⃣  Verificando tablas de base de datos...
   ✅ Tabla 'users' existe (3 registros)
   ✅ Tabla 'categories' existe (0 registros)
   ✅ Tabla 'products' existe (0 registros)
   ... (todas las tablas)

5️⃣  Verificando archivos de configuración...
   ✅ APP_KEY configurada
   ✅ DB_DATABASE configurada: Ikagai

✨ ¡TODO ESTÁ CORRECTO! El sistema está listo para usar.
```

---

## 🌱 Seeders

### 5. Crear usuarios de prueba
```bash
php artisan db:seed --class=RoleUsersSeeder
```

**Descripción:** Crea los 3 usuarios de prueba (admin, nutricionista, cliente).

**Usuarios creados:**
- **Admin:** admin@ikagai.com
- **Nutricionista:** nutritionist@ikagai.com
- **Cliente:** client@ikagai.com

Todos con contraseña: `password`

---

## 🧹 Limpieza de Caché

### 6. Limpiar todas las cachés
```bash
php artisan optimize:clear
```

**Descripción:** Limpia todas las cachés de Laravel:
- Cache de configuración
- Cache de rutas
- Cache de vistas
- Archivos compilados
- Cache de eventos

**Cuándo usar:**
- Después de cambiar configuraciones
- Cuando el login no funciona
- Después de agregar nuevas rutas
- Problemas generales de caché

---

## 📊 Flujo de Trabajo Recomendado

### Primera vez configurando el sistema:
```bash
# 1. Ejecutar migraciones
php artisan migrate

# 2. Crear usuarios de prueba
php artisan db:seed --class=RoleUsersSeeder

# 3. Verificar el sistema
php artisan system:verify

# 4. Listar usuarios creados
php artisan users:list
```

### Si el login no funciona:
```bash
# 1. Verificar el sistema
php artisan system:verify

# 2. Limpiar cachés
php artisan optimize:clear

# 3. Verificar usuarios
php artisan users:list

# 4. Probar login por comando
php artisan users:test-login admin@ikagai.com password

# 5. Si la contraseña falla, resetearla
php artisan users:reset-password admin@ikagai.com
```

### Resetear todo (CUIDADO: Borra todos los datos):
```bash
# Borrar y recrear todas las tablas con usuarios de prueba
php artisan migrate:fresh --seed

# O solo recrear usuarios
php artisan db:seed --class=RoleUsersSeeder --force
```

---

## 🎯 Casos de Uso

### Caso 1: Olvidé la contraseña de admin
```bash
php artisan users:reset-password admin@ikagai.com
```

### Caso 2: No sé qué usuarios existen
```bash
php artisan users:list
```

### Caso 3: El login no funciona
```bash
php artisan system:verify
php artisan optimize:clear
php artisan users:test-login admin@ikagai.com password
```

### Caso 4: Quiero crear un usuario con contraseña personalizada
```bash
# Primero, usa tinker
php artisan tinker

# Luego ejecuta:
$user = new App\Models\User();
$user->name = 'Nuevo Usuario';
$user->email = 'nuevo@ejemplo.com';
$user->password = Hash::make('miPassword123');
$user->role = 'client';
$user->save();
```

### Caso 5: Verificar que todo esté bien antes de probar
```bash
php artisan system:verify
```

---

## 💡 Tips y Trucos

### Ejecutar múltiples comandos en secuencia:
```bash
# PowerShell (Windows)
cd C:\xampp\htdocs\Ikagai; php artisan optimize:clear; php artisan system:verify

# Bash/CMD
php artisan optimize:clear && php artisan system:verify
```

### Ver todos los comandos disponibles:
```bash
php artisan list
```

### Ver comandos de usuarios:
```bash
php artisan list | grep users
```

### Ayuda de un comando específico:
```bash
php artisan help users:reset-password
```

---

## 🔗 Comandos Laravel Útiles

### Migraciones:
```bash
php artisan migrate               # Ejecutar migraciones
php artisan migrate:status        # Ver estado de migraciones
php artisan migrate:fresh         # Borrar y recrear todo
php artisan migrate:fresh --seed  # Borrar, recrear y sembrar datos
```

### Rutas:
```bash
php artisan route:list            # Ver todas las rutas
php artisan route:list --name=login # Filtrar por nombre
php artisan route:cache           # Cachear rutas (producción)
php artisan route:clear           # Limpiar cache de rutas
```

### Base de datos:
```bash
php artisan db:show               # Ver info de la BD
php artisan db:seed               # Ejecutar seeders
php artisan tinker                # Consola interactiva
```

### Cache:
```bash
php artisan cache:clear           # Limpiar cache de aplicación
php artisan config:clear          # Limpiar cache de config
php artisan view:clear            # Limpiar cache de vistas
php artisan optimize:clear        # Limpiar todo
```

---

## 📚 Archivos Relacionados

- **USUARIOS_Y_LOGIN.md** - Información de usuarios y acceso
- **SOLUCION_LOGIN.md** - Guía de solución de problemas
- **DOCUMENTACION_SISTEMA.md** - Documentación completa del sistema

---

## ✨ Resumen de Comandos Más Usados

```bash
# Ver usuarios
php artisan users:list

# Verificar sistema
php artisan system:verify

# Probar login
php artisan users:test-login admin@ikagai.com password

# Resetear contraseña
php artisan users:reset-password admin@ikagai.com

# Limpiar caché
php artisan optimize:clear

# Crear usuarios de prueba
php artisan db:seed --class=RoleUsersSeeder
```

---

¡Usa estos comandos para gestionar fácilmente tu sistema Ikagai! 🚀


# 🔐 Solución de Problemas de Login

## ✅ Estado Actual

Los usuarios de prueba están creados correctamente en la base de datos:

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@ikagai.com | password | Admin |
| nutritionist@ikagai.com | password | Nutricionista |
| client@ikagai.com | password | Cliente |

---

## 🛠️ Comandos Útiles Creados

### 1. Listar todos los usuarios
```bash
php artisan users:list
```

### 2. Probar login de un usuario
```bash
php artisan users:test-login admin@ikagai.com password
php artisan users:test-login nutritionist@ikagai.com password
php artisan users:test-login client@ikagai.com password
```

---

## 🔍 Verificaciones Realizadas

✅ **Usuarios creados** - Los 3 usuarios existen en la base de datos  
✅ **Contraseñas hasheadas** - Las contraseñas están correctamente encriptadas  
✅ **Roles asignados** - Cada usuario tiene su rol correcto  
✅ **Migraciones ejecutadas** - Todas las tablas están creadas  
✅ **Configuración de auth** - Laravel auth está configurado correctamente  

---

## 🐛 Posibles Problemas y Soluciones

### Problema 1: "Estas credenciales no coinciden con nuestros registros"

**Causas posibles:**
1. Cache de configuración
2. Email con mayúsculas/minúsculas
3. Espacios extra en el email
4. Cache de sesión

**Soluciones:**

```bash
# Limpiar cache de configuración
php artisan config:clear

# Limpiar cache de rutas
php artisan route:clear

# Limpiar cache de vistas
php artisan view:clear

# Limpiar todas las caches
php artisan optimize:clear
```

### Problema 2: Redirección incorrecta después del login

**Solución:**
Verifica el archivo `app/Providers/FortifyServiceProvider.php` en el método `boot()`:

```php
Fortify::authenticateUsing(function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->password, $user->password)) {
        return $user;
    }
});
```

### Problema 3: Página de login no se carga

**Verifica:**
1. Que la ruta `/login` esté disponible: `php artisan route:list | grep login`
2. Que la vista de login exista: `resources/views/auth/login.blade.php`

### Problema 4: Error de CSRF token

**Solución:**
```bash
# Regenerar la clave de la aplicación
php artisan key:generate

# Limpiar sesiones
php artisan session:table
php artisan migrate
```

---

## 🧪 Probar Login Manualmente

### Método 1: Usar el formulario web
1. Ve a: `http://localhost/Ikagai/public/login` o tu URL local
2. Usa las credenciales:
   - Email: `admin@ikagai.com`
   - Password: `password`

### Método 2: Usar Artisan Tinker
```bash
php artisan tinker
```

Luego ejecuta:
```php
$user = App\Models\User::where('email', 'admin@ikagai.com')->first();
echo $user ? "Usuario encontrado: {$user->name}" : "Usuario no encontrado";

// Verificar contraseña
Hash::check('password', $user->password); // Debe devolver true
```

---

## 🔄 Recrear Usuarios (si es necesario)

Si los usuarios no funcionan, puedes eliminarlos y recrearlos:

```bash
# Método 1: Recrear todo (CUIDADO: Elimina todos los datos)
php artisan migrate:fresh --seed

# Método 2: Solo recrear usuarios de prueba
php artisan db:seed --class=RoleUsersSeeder
```

---

## 📝 Verificar Configuración de Fortify

Archivo: `config/fortify.php`

Verifica que estas líneas estén así:
```php
'guard' => 'web',
'username' => 'email',
'lowercase_usernames' => true,
```

---

## 🌐 Verificar Variables de Entorno (.env)

Asegúrate de que tu archivo `.env` tenga:

```env
APP_NAME=Ikagai
APP_ENV=local
APP_KEY=base64:... (debe estar generada)
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=root
DB_PASSWORD=tu_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

## 🚀 Pasos de Depuración

### 1. Verificar que los usuarios existen
```bash
php artisan users:list
```

### 2. Probar login por comando
```bash
php artisan users:test-login admin@ikagai.com password
```

### 3. Limpiar todas las caches
```bash
php artisan optimize:clear
```

### 4. Verificar rutas de autenticación
```bash
php artisan route:list --name=login
```

### 5. Verificar logs
Revisa el archivo: `storage/logs/laravel.log` para ver errores específicos.

---

## 💡 Consejos Adicionales

1. **Asegúrate de estar usando el navegador correcto** - Limpia las cookies del navegador o usa modo incógnito

2. **Verifica que XAMPP esté corriendo** - MySQL y Apache deben estar activos

3. **Verifica la URL correcta** - Debe ser algo como:
   - `http://localhost/Ikagai/public/login` o
   - `http://ikagai.test/login` (si usas Valet/Laragon)

4. **Email con minúsculas** - Fortify convierte los emails a minúsculas por defecto

5. **Sesión activa** - Si ya iniciaste sesión, cierra sesión primero

---

## 🔧 Si nada funciona...

Ejecuta estos comandos en orden:

```bash
# 1. Limpiar todo
php artisan optimize:clear

# 2. Listar usuarios
php artisan users:list

# 3. Si no hay usuarios, crearlos
php artisan db:seed --class=RoleUsersSeeder

# 4. Probar login por comando
php artisan users:test-login admin@ikagai.com password

# 5. Generar nueva clave de app (si es necesario)
php artisan key:generate

# 6. Reiniciar servidor
# Detén e inicia nuevamente tu servidor web
```

---

## 📞 Información de Contacto de Depuración

Si aún tienes problemas, verifica:
- ✅ Logs de Laravel: `storage/logs/laravel.log`
- ✅ Logs de Apache: `C:\xampp\apache\logs\error.log`
- ✅ Logs de MySQL: `C:\xampp\mysql\data\*.err`

---

## ✨ Usuarios Disponibles

Una vez que todo funcione, usa estas credenciales:

### 👨‍💼 Administrador
- **Email:** admin@ikagai.com
- **Password:** password
- **Rol:** admin
- **Acceso:** Panel completo de administración

### 👨‍⚕️ Nutricionista
- **Email:** nutritionist@ikagai.com
- **Password:** password
- **Rol:** nutritionist
- **Acceso:** Gestión de servicios, planes, reservas

### 👤 Cliente
- **Email:** client@ikagai.com
- **Password:** password
- **Rol:** client
- **Acceso:** Realizar pedidos, reservar consultas, ver planes


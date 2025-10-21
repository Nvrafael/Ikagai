# Documentación del Sistema de Nutrición

## 📋 Resumen

Se han creado todas las migraciones, modelos y controladores necesarios para un sistema completo de nutrición que incluye:

- ✅ Gestión de productos y categorías
- ✅ Sistema de reseñas
- ✅ Servicios del nutricionista
- ✅ Reservas de consultas
- ✅ Gestión de pedidos
- ✅ Planes nutricionales
- ✅ Sistema de mensajería

---

## 📦 Entidades Creadas

### 1. **Categories** (Categorías)
**Migración:** `2025_10_21_000001_create_categories_table.php`  
**Modelo:** `Category.php`  
**Controlador:** `CategoryController.php`

**Campos:**
- `name` - Nombre de la categoría
- `slug` - URL amigable
- `description` - Descripción
- `image` - Imagen de la categoría
- `is_active` - Estado activo/inactivo

**Relaciones:**
- `hasMany` → Products

---

### 2. **Products** (Productos)
**Migración:** `2025_10_21_000002_create_products_table.php`  
**Modelo:** `Product.php`  
**Controlador:** `ProductController.php`

**Campos:**
- `category_id` - Categoría del producto
- `name`, `slug`, `description` - Información básica
- `price` - Precio actual
- `compare_price` - Precio de comparación (antes/descuento)
- `stock` - Cantidad en inventario
- `sku` - Código de producto
- `images` - Múltiples imágenes (JSON)
- `is_active` - Estado activo/inactivo
- `is_featured` - Producto destacado
- `benefits` - Beneficios del producto
- `ingredients` - Ingredientes
- `weight` - Peso/tamaño

**Relaciones:**
- `belongsTo` → Category
- `hasMany` → Reviews
- `hasMany` → OrderItems

**Métodos útiles:**
- `averageRating()` - Calcula el promedio de calificaciones
- `hasStock($quantity)` - Verifica disponibilidad

---

### 3. **Reviews** (Reseñas)
**Migración:** `2025_10_21_000003_create_reviews_table.php`  
**Modelo:** `Review.php`  
**Controlador:** `ReviewController.php`

**Campos:**
- `product_id` - Producto reseñado
- `user_id` - Usuario que hizo la reseña
- `rating` - Calificación (1-5)
- `title` - Título de la reseña
- `comment` - Comentario
- `is_verified_purchase` - Compra verificada
- `is_approved` - Aprobada por admin

**Restricciones:**
- Un usuario solo puede dejar una reseña por producto

**Relaciones:**
- `belongsTo` → Product
- `belongsTo` → User

---

### 4. **Services** (Servicios)
**Migración:** `2025_10_21_000004_create_services_table.php`  
**Modelo:** `Service.php`  
**Controlador:** `ServiceController.php`

**Campos:**
- `name`, `slug`, `description` - Información básica
- `price` - Precio del servicio
- `duration` - Duración en minutos
- `type` - Tipo de servicio (consultation, nutritional_plan, follow_up, workshop)
- `is_active` - Estado activo/inactivo
- `image` - Imagen del servicio
- `includes` - Qué incluye el servicio

**Relaciones:**
- `hasMany` → Bookings

---

### 5. **Bookings** (Reservas)
**Migración:** `2025_10_21_000005_create_bookings_table.php`  
**Modelo:** `Booking.php`  
**Controlador:** `BookingController.php`

**Campos:**
- `user_id` - Cliente que reserva
- `service_id` - Servicio reservado
- `scheduled_at` - Fecha y hora de la cita
- `status` - Estado (pending, confirmed, completed, cancelled)
- `notes` - Notas del cliente
- `nutritionist_notes` - Notas del nutricionista
- `price` - Precio en el momento de la reserva
- `payment_status` - Estado de pago
- `meeting_link` - Link de reunión virtual

**Relaciones:**
- `belongsTo` → User
- `belongsTo` → Service
- `hasOne` → NutritionalPlan

**Métodos útiles:**
- `isConfirmed()` - Verifica si está confirmada
- `isCompleted()` - Verifica si está completada

---

### 6. **Orders** (Pedidos) y **OrderItems** (Items de Pedido)
**Migraciones:**
- `2025_10_21_000006_create_orders_table.php`
- `2025_10_21_000007_create_order_items_table.php`

**Modelos:** `Order.php`, `OrderItem.php`  
**Controlador:** `OrderController.php`

**Campos de Order:**
- `user_id` - Cliente
- `order_number` - Número de pedido único
- `status` - Estado (pending, processing, shipped, delivered, cancelled)
- `subtotal`, `tax`, `shipping`, `total` - Montos
- `payment_method`, `payment_status` - Información de pago
- `shipping_*` - Datos de envío completos
- `notes` - Notas adicionales
- `tracking_number` - Número de seguimiento

**Campos de OrderItem:**
- `order_id` - Pedido al que pertenece
- `product_id` - Producto
- `quantity` - Cantidad
- `price` - Precio en el momento de la compra
- `subtotal` - Subtotal del item

**Relaciones:**
- Order `belongsTo` → User
- Order `hasMany` → OrderItems
- OrderItem `belongsTo` → Order
- OrderItem `belongsTo` → Product

**Métodos útiles:**
- `Order::generateOrderNumber()` - Genera número único de pedido

---

### 7. **NutritionalPlans** (Planes Nutricionales)
**Migración:** `2025_10_21_000008_create_nutritional_plans_table.php`  
**Modelo:** `NutritionalPlan.php`  
**Controlador:** `NutritionalPlanController.php`

**Campos:**
- `user_id` - Cliente
- `booking_id` - Consulta relacionada (opcional)
- `title`, `description` - Información básica
- `objectives` - Objetivos nutricionales (JSON)
- `dietary_restrictions` - Restricciones alimentarias (JSON)
- `meal_plan` - Plan de comidas (JSON)
- `recommendations` - Recomendaciones generales
- `target_calories` - Calorías objetivo
- `current_weight`, `target_weight` - Pesos
- `start_date`, `end_date` - Fechas de vigencia
- `is_active` - Estado activo
- `status` - Estado (active, completed, cancelled)

**Relaciones:**
- `belongsTo` → User
- `belongsTo` → Booking

**Métodos útiles:**
- `isActive()` - Verifica si está activo

---

### 8. **Messages** (Mensajes)
**Migración:** `2025_10_21_000009_create_messages_table.php`  
**Modelo:** `Message.php`  
**Controlador:** `MessageController.php`

**Campos:**
- `sender_id` - Quien envía
- `receiver_id` - Quien recibe
- `message` - Contenido del mensaje
- `is_read` - Si fue leído
- `read_at` - Cuándo fue leído
- `attachments` - Archivos adjuntos (JSON)

**Relaciones:**
- `belongsTo` → User (sender)
- `belongsTo` → User (receiver)

**Métodos útiles:**
- `markAsRead()` - Marcar como leído
- `Message::conversation($userId1, $userId2)` - Obtener conversación

---

## 🔧 Modelo User Actualizado

Se agregaron las siguientes relaciones al modelo User:

```php
- reviews() - Reseñas del usuario
- bookings() - Reservas del usuario
- orders() - Pedidos del usuario
- nutritionalPlans() - Planes nutricionales
- sentMessages() - Mensajes enviados
- receivedMessages() - Mensajes recibidos

// Métodos helpers:
- isNutritionist() - Verifica si es nutricionista
- isClient() - Verifica si es cliente
- isAdmin() - Verifica si es administrador
```

---

## 🛣️ Rutas

Se creó el archivo `routes/example_routes.php` con todas las rutas sugeridas organizadas por:

1. **Rutas Públicas** - Sin autenticación (ver productos, servicios, categorías)
2. **Rutas Autenticadas** - Para usuarios logueados (hacer pedidos, reservas, mensajes)
3. **Rutas de Nutricionista** - Gestión de servicios, planes, reservas
4. **Rutas de Administrador** - Gestión de pedidos, aprobación de reseñas

---

## 📝 Próximos Pasos

### 1. Ejecutar las Migraciones

```bash
php artisan migrate
```

### 2. Agregar las Rutas

Copia las rutas del archivo `routes/example_routes.php` a tu archivo `routes/web.php`.

### 3. Crear las Vistas

Necesitarás crear las vistas Blade para:
- Listados de productos, servicios, categorías
- Detalle de producto con reseñas
- Formularios de reserva
- Carrito de compras
- Checkout de pedidos
- Panel de mensajes
- Dashboard de nutricionista con planes

### 4. Configuración Adicional

**Storage público:**
```bash
php artisan storage:link
```

**Configurar filesystems** en `config/filesystems.php` si usarás archivos adjuntos en mensajes.

### 5. Seeders (Opcional)

Puedes crear seeders para datos de prueba:
```bash
php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder
php artisan make:seeder ServiceSeeder
```

---

## 🎨 Funcionalidades Implementadas

### Sistema de Productos
- ✅ Categorización de productos
- ✅ Múltiples imágenes por producto
- ✅ Gestión de stock
- ✅ Precios de comparación (descuentos)
- ✅ Productos destacados
- ✅ Búsqueda y filtros

### Sistema de Reseñas
- ✅ Calificación de 1 a 5 estrellas
- ✅ Una reseña por usuario por producto
- ✅ Compra verificada
- ✅ Aprobación por administrador

### Sistema de Reservas
- ✅ Diferentes tipos de servicios
- ✅ Estados de reserva
- ✅ Notas de cliente y nutricionista
- ✅ Link de reunión virtual
- ✅ Gestión de pagos

### Sistema de Pedidos
- ✅ Carrito de compras completo
- ✅ Cálculo automático de impuestos y envío
- ✅ Datos de envío completos
- ✅ Número de pedido único
- ✅ Estados de pedido
- ✅ Seguimiento

### Planes Nutricionales
- ✅ Objetivos personalizados
- ✅ Restricciones alimentarias
- ✅ Plan de comidas en JSON
- ✅ Seguimiento de peso
- ✅ Vinculación con reservas

### Sistema de Mensajería
- ✅ Chat entre usuario y nutricionista
- ✅ Archivos adjuntos
- ✅ Estado de lectura
- ✅ Contador de no leídos

---

## 🔒 Seguridad y Validaciones

Todos los controladores incluyen:
- ✅ Validación de datos de entrada
- ✅ Verificación de permisos (usuarios solo pueden ver/editar sus propios datos)
- ✅ Protección contra duplicados
- ✅ Transacciones de base de datos para operaciones críticas
- ✅ Sanitización de slugs

---

## 💡 Consejos de Implementación

1. **Carrito de Compras**: Considera usar sesiones o una tabla `cart_items` para persistir el carrito.

2. **Pagos**: Integra con pasarelas como Stripe, PayPal o MercadoPago.

3. **Notificaciones**: Implementa notificaciones por email cuando:
   - Se crea una reserva
   - Se confirma una reserva
   - Se crea un pedido
   - Llega un nuevo mensaje

4. **API**: Todos estos controladores pueden adaptarse fácilmente para crear una API REST.

5. **Permisos**: El middleware `RoleMiddleware` ya existe, úsalo en las rutas con roles específicos.

---

## 📚 Estructura de Archivos Creados

```
database/migrations/
├── 2025_10_21_000001_create_categories_table.php
├── 2025_10_21_000002_create_products_table.php
├── 2025_10_21_000003_create_reviews_table.php
├── 2025_10_21_000004_create_services_table.php
├── 2025_10_21_000005_create_bookings_table.php
├── 2025_10_21_000006_create_orders_table.php
├── 2025_10_21_000007_create_order_items_table.php
├── 2025_10_21_000008_create_nutritional_plans_table.php
└── 2025_10_21_000009_create_messages_table.php

app/Models/
├── Category.php
├── Product.php
├── Review.php
├── Service.php
├── Booking.php
├── Order.php
├── OrderItem.php
├── NutritionalPlan.php
├── Message.php
└── User.php (actualizado)

app/Http/Controllers/
├── CategoryController.php
├── ProductController.php
├── ReviewController.php
├── ServiceController.php
├── BookingController.php
├── OrderController.php
├── NutritionalPlanController.php
└── MessageController.php

routes/
└── example_routes.php
```

---

## ✨ ¡Listo para usar!

El sistema está completo y listo para que comiences a crear las vistas y personalizar según tus necesidades específicas.


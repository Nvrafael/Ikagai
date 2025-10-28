# Vista del Nutricionista - Documentación

## 📋 Descripción

Se ha creado una página completa y funcional para mostrar el perfil profesional del nutricionista y permitir a los usuarios reservar citas online.

## 🎯 Características Implementadas

### ✅ Estructura de la Vista

1. **Encabezado Hero**
   - Título principal: "Tu nutricionista de confianza"
   - Subtítulo descriptivo
   - Badge destacado con "Nutrición Profesional"
   - Colores: Verde suave (#9CAF88) y Beige (#F5F1E8)

2. **Sección de Información del Nutricionista**
   - Foto/Avatar profesional (placeholder personalizable)
   - Nombre: Laura Pérez
   - Especialidad: Nutricionista Certificada
   - Descripción profesional (3 párrafos sobre filosofía y enfoque)
   - Tabla de datos profesionales:
     * Titulación
     * Experiencia (+10 años)
     * Especialidades
     * Idiomas

3. **Áreas de Especialidad**
   - Nutrición Deportiva
   - Bienestar Integral
   - Planes Personalizados
   - Educación Nutricional

4. **Formulario de Reserva de Cita**
   - Nombre completo (autocompletado desde el usuario autenticado)
   - Email (autocompletado)
   - Teléfono (validación formato español)
   - Selector de fecha (mínimo: mañana)
   - Selector de hora (horarios predefinidos: 9:00-13:00, 16:00-19:00)
   - Motivo de consulta (textarea con validación mínimo 20 caracteres)
   - Botón "Confirmar cita" con animación de envío

5. **Sección de Contacto Directo**
   - Botón de WhatsApp con enlace directo (personalizable)
   - Botón de Email (mailto:nutricionista@ikigai.com)
   - Diseño con gradientes y efectos hover
   - Horario de atención detallado

6. **Diseño Visual**
   - Estilo minimalista y profesional
   - Colores: Verde suave (#9CAF88), Beige (#F5F1E8), Negro, Blanco
   - Animaciones suaves de entrada (fadeInUp)
   - Totalmente responsivo (mobile-first)
   - Efectos hover en botones y enlaces
   - Bordes y sombras sutiles

## 🔧 Archivos Modificados/Creados

### Nuevos Archivos
1. **`/resources/views/nutricionista.blade.php`**
   - Vista completa del nutricionista
   - HTML + CSS inline + JavaScript de validación

### Archivos Modificados
1. **`/routes/web.php`**
   - Añadida ruta: `/nutricionista` → `nutritionist.profile`

2. **`/app/Http/Controllers/BookingController.php`**
   - Actualizado método `store()` para manejar:
     * Campo `phone` (teléfono)
     * Validación mínimo 20 caracteres en `notes`
     * Redirección mejorada con mensaje de éxito

3. **`/resources/views/welcome.blade.php`**
   - Actualizado botón en tarjetas de nutricionistas
   - Enlace al perfil del nutricionista

## 🌐 Rutas Disponibles

### Ruta Principal
```php
GET /nutricionista
Nombre: nutritionist.profile
```

**Accesible públicamente** (no requiere autenticación para ver)
- Los usuarios NO autenticados verán un mensaje para iniciar sesión
- Los usuarios autenticados pueden reservar citas directamente

## 💾 Sistema de Reservas

### Integración con Sistema Existente
El formulario se integra con el sistema de `bookings` existente:

- **Tabla**: `bookings`
- **Campos utilizados**:
  - `user_id` → Usuario autenticado
  - `service_id` → Servicio de consulta nutricional
  - `scheduled_at` → Fecha y hora combinadas
  - `notes` → Incluye teléfono + motivo de consulta
  - `price` → Precio del servicio
  - `status` → 'pending' por defecto

### Validaciones del Formulario

1. **JavaScript (Frontend)**:
   - Teléfono español válido: +34 / 6-9 + 8 dígitos
   - Motivo mínimo 20 caracteres
   - Fecha y hora seleccionadas

2. **PHP (Backend)**:
   - `service_id`: requerido, debe existir
   - `scheduled_at`: requerido, fecha futura
   - `phone`: opcional, máximo 20 caracteres
   - `notes`: requerido, mínimo 20 caracteres

## 🎨 Personalización

### Cambiar la Foto del Nutricionista
Reemplaza el placeholder SVG en la línea ~132:
```html
<!-- Reemplaza el div con avatar SVG por: -->
<img src="/ruta/a/foto.jpg" alt="Laura Pérez" class="w-64 h-64 object-cover">
```

### Cambiar Datos del Nutricionista
Edita el archivo `/resources/views/nutricionista.blade.php`:
- Nombre: línea ~142
- Especialidad: línea ~147
- Descripción: líneas ~163-179
- Datos profesionales: líneas ~152-169

### Personalizar WhatsApp
Línea ~553:
```html
href="https://wa.me/346XXXXXXXX?text=..."
```
Reemplaza `346XXXXXXXX` con el número real (sin espacios ni símbolos)

### Personalizar Email
Línea ~577:
```html
href="mailto:nutricionista@ikigai.com?subject=..."
```

### Cambiar Horarios Disponibles
Líneas ~387-395:
```html
<option value="09:00">09:00</option>
<!-- Añade o elimina horarios según necesites -->
```

## 🚀 Uso

### 1. Acceder a la Vista
```
http://tu-dominio.com/nutricionista
```

### 2. Como Usuario No Autenticado
- Puede ver toda la información del nutricionista
- Puede ver el formulario pero con botones para login/registro
- Puede usar los botones de contacto directo (WhatsApp/Email)

### 3. Como Usuario Autenticado
- Formulario de reserva completamente funcional
- Campos de nombre y email pre-rellenados
- Al enviar, se crea una reserva en estado "pending"
- Mensaje de confirmación tras envío exitoso

### 4. Validación de Errores
El formulario muestra mensajes claros si:
- El teléfono no es válido
- El motivo es muy corto (< 20 caracteres)
- Falta seleccionar fecha u hora
- El horario ya está reservado

## 📱 Responsive Design

La vista está optimizada para:
- 📱 Móviles (< 640px)
- 📱 Tablets (640px - 1024px)
- 💻 Desktop (> 1024px)

### Características Responsive
- Grid de 1 columna en móvil, 2 en desktop
- Imágenes y textos adaptativos
- Navegación colapsable
- Formulario adaptable

## 🎭 Animaciones

### Efectos Incluidos
1. **fadeInUp**: Elementos aparecen desde abajo
2. **Delays**: Animaciones secuenciales (.delay-1, .delay-2, .delay-3)
3. **Hover effects**: 
   - Transformación Y en tarjetas de contacto
   - Cambios de opacidad en bordes
   - Transiciones suaves de color

## 🔒 Seguridad

- Token CSRF incluido en el formulario
- Validación en frontend y backend
- Protección contra SQL injection (Eloquent)
- Sanitización de inputs

## 📊 Testing

### Probar la Vista
1. Inicia el servidor: `php artisan serve`
2. Visita: `http://localhost:8000/nutricionista`
3. Verifica:
   - ✅ La página carga correctamente
   - ✅ Las animaciones funcionan
   - ✅ Los botones de contacto abren correctamente
   - ✅ El formulario (si estás autenticado)

### Probar Reserva
1. Inicia sesión como cliente
2. Ve a `/nutricionista`
3. Completa el formulario
4. Verifica que se cree la reserva en la base de datos:
   ```sql
   SELECT * FROM bookings ORDER BY created_at DESC LIMIT 1;
   ```

## ⚠️ Notas Importantes

1. **Servicio Requerido**: La vista necesita al menos un servicio activo en la tabla `services`. Si no existe, se usa un servicio "fantasma" con ID 1.

2. **Crear Servicio**: Si es necesario, crea un servicio:
   ```php
   php artisan tinker
   
   Service::create([
       'name' => 'Consulta Nutricional',
       'slug' => 'consulta-nutricional',
       'description' => 'Consulta personalizada con nutricionista',
       'price' => 50.00,
       'duration' => 60,
       'type' => 'online',
       'is_active' => true
   ]);
   ```

3. **Teléfono en Notas**: El campo `phone` se guarda dentro de `notes` porque la tabla `bookings` no tiene columna `phone`. Si quieres una columna separada, crea una migración.

## 🎨 Colores del Proyecto

```css
/* Colores principales */
bg-sage: #9CAF88 (Verde suave)
bg-sage-light: #E8F5E9 (Verde muy claro)
bg-beige: #F5F1E8 (Beige cálido)
text-sage: #7A9A65 (Verde texto)

/* Colores base IKIGAI */
Negro: #000000
Blanco: #FFFFFF
Gris: #F9FAFB (fondo)
```

## 📝 Próximas Mejoras Sugeridas

1. **Sistema de Calendario**: Integrar fullcalendar.js para visualizar disponibilidad
2. **Confirmación por Email**: Enviar email automático tras reservar
3. **Recordatorios**: Sistema de notificaciones 24h antes
4. **Videollamada**: Integrar Zoom/Google Meet
5. **Pagos Online**: Stripe o PayPal para pago directo
6. **Reseñas**: Sistema de valoraciones de consultas

## 🐛 Troubleshooting

### Error: "Service not found"
**Solución**: Crea un servicio en la base de datos (ver sección "Notas Importantes")

### Error: "CSRF token mismatch"
**Solución**: Limpia caché de Laravel
```bash
php artisan cache:clear
php artisan config:clear
```

### La página no carga estilos
**Solución**: Compila assets
```bash
npm run dev
# o
npm run build
```

### El formulario no envía
**Solución**: Verifica:
1. Estás autenticado
2. El servicio existe
3. JavaScript está habilitado
4. No hay errores en consola del navegador

## 📞 Soporte

Para cualquier duda o problema, contacta al equipo de desarrollo.

---

**Creado para**: IKIGAI - Plataforma de Bienestar y Nutrición
**Versión**: 1.0
**Fecha**: 2025-10-28

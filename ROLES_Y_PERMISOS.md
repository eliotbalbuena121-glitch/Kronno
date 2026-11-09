# Sistema de Roles y Permisos - KRONO 2026

## Descripción General

El sistema KRONO 2026 implementa un control de acceso basado en dos roles principales: **Profesor** y **Alumno**, cada uno con permisos específicos y vistas personalizadas.

---

## Roles

### 1. PROFESOR (Administrador)

Los profesores tienen acceso completo al sistema para gestionar la asistencia de los alumnos.

#### Permisos:
- ✅ **Registrar Asistencia**: Crear registros de presencia/ausencia/tardanza
- ✅ **Gestionar Alumnos**: Ver, crear, editar y eliminar alumnos
- ✅ **Gestionar Cursos**: Administrar cursos y secciones
- ✅ **Gestionar Profesores**: Ver y administrar el plantel docente
- ✅ **Ver Justificativos**: Revisar y aprobar/rechazar justificativos de alumnos
- ✅ **Cargar Asistencia por Curso**: Registro masivo por clase
- ✅ **Reportes**: Generar reportes de asistencia
- ✅ **Consultas**: Realizar consultas avanzadas

#### Vistas Disponibles:
- Dashboard con estadísticas de todo el sistema
- Página de Asistencias (creación y historial)
- Página de Alumnos
- Página de Cursos
- Página de Profesores
- Página de Justificativos
- Página de Cargar Asistencia por Curso
- Página de Consultas
- Página de Reportes

---

### 2. ALUMNO (Usuario Limitado)

Los alumnos tienen acceso limitado para visualizar su información personal de asistencia.

#### Permisos:
- ✅ **Ver Mis Asistencias**: Consultar su registro personal de asistencia
- ✅ **Crear Justificativos**: Enviar justificativos por ausencias/tardanzas
- ✅ **Ver Mis Justificativos**: Consultar el estado de sus justificativos
- ✅ **Debatir Justificativos**: Dejar comentarios en justificativos pendientes
- ❌ NO puede crear o modificar asistencias
- ❌ NO puede acceder a datos de otros alumnos
- ❌ NO puede ver reportes del sistema
- ❌ NO puede gestionar usuarios

#### Vistas Disponibles:
- Dashboard personalizado con estadísticas personales
- Página de Mis Asistencias
- Página de Mis Justificativos
- Página de Mi Perfil

---

## Estructura de Autenticación

### Tabla de Usuarios

```sql
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(120) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  nombre VARCHAR(140) NOT NULL,
  rol ENUM('profesor', 'alumno') NOT NULL,
  alumno_id INT NULL,
  profesor_id INT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(alumno_id) REFERENCES alumnos(id),
  FOREIGN KEY(profesor_id) REFERENCES profesores(id)
);
```

### Componentes de Autenticación

- **login.php**: Página de inicio de sesión
- **logout.php**: Cierre de sesión
- **auth.php**: Funciones de autenticación y validación
- **perfil.php**: Página de perfil del usuario

---

## Funciones de Control de Acceso

Las siguientes funciones están disponibles en `auth.php`:

### Verificación de Autenticación

```php
is_logged_in()          // Verifica si el usuario está autenticado
get_user()              // Obtiene datos del usuario autenticado
get_rol()               // Obtiene el rol del usuario
has_role($role)         // Verifica si el usuario tiene un rol específico
```

### Funciones Específicas

```php
is_profesor()           // Verifica si es profesor
is_alumno()             // Verifica si es alumno
require_login()         // Requiere autenticación
require_profesor()      // Requiere que sea profesor
require_alumno()        // Requiere que sea alumno
```

### Funciones de Sesión

```php
login_user($email, $password)    // Autentica un usuario
logout_user()                     // Cierra la sesión
get_user_full_data()             // Obtiene datos completos del usuario
```

---

## Archivos Principales

### Autenticación y Control de Acceso
- `auth.php` - Funciones de autenticación y validación
- `login.php` - Página de inicio de sesión
- `logout.php` - Cierre de sesión
- `config.php` - Configuración general

### Páginas del Sistema
- `index.php` - Dashboard (diferente para cada rol)
- `header.php` - Navegación (diferente para cada rol)
- `perfil.php` - Perfil del usuario
- `asistencias.php` - Gestión de asistencias (solo profesor)
- `mis-asistencias.php` - Asistencias personales (solo alumno)
- `mis-justificativos.php` - Gestión de justificativos (solo alumno)
- `justificativos.php` - Gestión de justificativos (solo profesor)

---

## Flujo de Autenticación

```
1. Usuario accede a cualquier página
2. header.php verifica si está autenticado
3. Si NO está autenticado → Redirige a login.php
4. Usuario ingresa email y contraseña
5. Se valida contra tabla usuarios
6. Si es válido → Sesión se crea
7. Usuario accede a la página solicitada
8. El sistema verifica el rol
9. Muestra contenido específico según el rol
```

---

## Cuentas de Prueba

### Profesores
- Email: `eliot@krono.edu.py`
- Email: `giovanna@krono.edu.py`
- Contraseña: `cualquier` (en modo de desarrollo)

### Alumnos
- Email: `eliot.alumno@correo.com`
- Email: `giovanna.alumno@correo.com`
- Contraseña: `cualquier` (en modo de desarrollo)

---

## Mensajes de Error

- **Acceso denegado**: Se muestra cuando un usuario intenta acceder a una página restringida para su rol
- **No autenticado**: Se redirige a login.php si el usuario no está autenticado
- **Credenciales inválidas**: Se muestra en login.php si email/contraseña no coinciden

---

## Consideraciones de Seguridad

### Implementación Actual (Desarrollo)
- Las contraseñas se aceptan sin validación (para facilitar pruebas)
- Las sesiones se almacenan en la cookie PHPSESSID

### Para Producción
1. Implementar `password_hash()` y `password_verify()` para contraseñas
2. Usar HTTPS en todos los endpoints
3. Implementar CSRF tokens en formularios
4. Agregar rate limiting en login.php
5. Implementar 2FA (autenticación de dos factores)
6. Encriptar datos sensibles
7. Auditar cambios en justificativos
8. Implementar system de backup de sesiones

---

## Próximas Mejoras

- [ ] Aprobar/rechazar justificativos desde interfaz profesor
- [ ] Comentarios bidireccionales en justificativos
- [ ] Cambio de contraseña
- [ ] Recuperación de contraseña
- [ ] Notificaciones por email
- [ ] Gestión de roles administrativos
- [ ] Auditoría de cambios
- [ ] Exportación de reportes

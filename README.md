# 🎓 KRONO 2026 - Sistema de Gestión de Asistencias

Sistema completo de gestión de asistencias escolares desarrollado para el Colegio KRONO, diseñado para profesores y alumnos.

**Versión:** 2.0 (Top 3 Features - Completo)  
**Estado:** ✅ Producción Lista  
**Última actualización:** Noviembre 2026

---

## 🚀 Inicio Rápido

### Requisitos
- PHP 7.4+
- MySQL 5.7+ o MariaDB
- Servidor web (Apache, Nginx)
- Navegador moderno

### Instalación

1. **Clonar o descargar el proyecto**
```bash
cd /ruta/del/servidor/web
# Copiar archivos aquí
```

2. **Crear base de datos**
```bash
mysql -u root < database.sql
```

3. **Configurar conexión** (editar `conexion.php`)
```php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "kronno2026";
```

4. **Acceder**
- Abre: `http://localhost/KRONNO/`
- Serás redirigido a login

5. **Primeras cuentas**
- Ejecuta registro para crear profesores y alumnos

---

## 📋 Documentación Principal

### Antes de Empezar
- 📖 [GUIA_TESTING.md](GUIA_TESTING.md) - **LEER PRIMERO** para testing manual
- 🔧 [IMPLEMENTACION.md](IMPLEMENTACION.md) - Detalles técnicos de features

### Información del Sistema
- 👥 [ROLES_Y_PERMISOS.md](ROLES_Y_PERMISOS.md) - Permisos por rol
- 🗄️ [database.sql](database.sql) - Estructura de BD y datos

---

## ✨ Top 3 Features Implementadas

### 1️⃣ Dashboard con Gráficos 📊
**Archivo:** `dashboard.php`

Visualización completa del sistema con:
- 📈 Gráficos interactivos (Chart.js)
- 📊 Estadísticas en tarjetas
- 📋 Tablas de análisis
- ⚠️ Alumnos con baja asistencia

**Acceso:** Solo profesores  
**Menú:** Dashboard (icono de gráfico)

---

### 2️⃣ Búsqueda Avanzada 🔍
**Archivo:** `alumnos.php`

Gestión avanzada de alumnos:
- 🔎 Búsqueda en tiempo real (nombre, documento, email)
- 🎯 Filtros por curso
- 📊 Ordenamiento interactivo
- ➕ Agregar nuevos alumnos

**Acceso:** Solo profesores  
**Menú:** Alumnos

---

### 3️⃣ Exportar Reportes 📥
**Archivo:** `exportar.php`

Exportación flexible de datos:
- 📑 3 tipos de reportes (Asistencias, Justificativos, Alumnos)
- 🔍 Filtros dinámicos
- 👁️ Vista previa antes de descargar
- 📄 Descarga CSV (Excel compatible)

**Acceso:** Solo profesores  
**Menú:** Exportar (icono de descarga)

---

## 🏗️ Arquitectura

### Estructura de Carpetas
```
KRONNO/
├── lib/
│   ├── charts.php          # Funciones para datos de gráficos
│   ├── export.php          # Funciones para exportación
│   └── utils.ts            # Utilidades TypeScript
├── public/                 # Imágenes y assets
├── app/                    # Componentes Next.js (NO USADO)
│
├── dashboard.php           # 📊 Dashboard
├── exportar.php            # 📥 Exportación de reportes
├── alumnos.php             # 👥 Búsqueda avanzada
│
├── index.php               # Página de inicio
├── login.php               # Login
├── logout.php              # Logout
├── header.php              # Navegación
├── footer.php              # Pie de página
│
├── auth.php                # Autenticación y roles
├── conexion.php            # Conexión a BD
├── config.php              # Configuración general
├── database.sql            # BD y datos
│
└── [otros archivos]        # Reportes, justificativos, etc.
```

### Stack Tecnológico

**Backend:**
- PHP 7.4+
- MySQL 5.7+
- PDO (conexión a BD)

**Frontend:**
- HTML5
- Bootstrap 5.3.3
- Chart.js 4.4.0
- FontAwesome 6.5.2
- JavaScript vanilla

**Seguridad:**
- Prepared statements (SQL Injection prevention)
- HTML escaping (XSS prevention)
- Autenticación basada en sesiones
- Control de acceso por rol

---

## 👥 Roles y Acceso

### Profesor 👨‍🏫
**Permisos completos:**
- ✅ Ver Dashboard con gráficos
- ✅ Gestionar alumnos (búsqueda, agregar)
- ✅ Gestionar cursos y profesores
- ✅ Registrar asistencias
- ✅ Gestionar justificativos
- ✅ Exportar reportes
- ✅ Ver reportes y consultas

### Alumno 🎓
**Permisos limitados:**
- ✅ Ver mis asistencias
- ✅ Ver mis justificativos
- ✅ Editar mi perfil
- ❌ No acceso a Dashboard
- ❌ No acceso a Exportar
- ❌ No acceso a gestión de usuarios

---

## 📊 Datos de Prueba

La BD incluye datos precargados:

| Entidad | Cantidad | Descripción |
|---------|----------|-------------|
| Cursos | 10 | Desde 1ro a 6to, turnos Mañana/Tarde |
| Profesores | 10 | Con emails y especialidades |
| Alumnos | 10 | Asignados a diferentes cursos |
| Asistencias | 10 | Registros de 2026-08-18 |
| Justificativos | 10 | Estados variados |

---

## 🔍 Testing

### Flujo Básico de Testing

1. **Crear cuentas**
   - Registro profesor: `registro-profesor.php`
   - Registro alumno: `registro-alumno.php`

2. **Login y navegación**
   - Login con las cuentas creadas
   - Verificar roles y permisos

3. **Probar features**
   - Dashboard → Ver gráficos
   - Alumnos → Búsqueda y filtros
   - Exportar → Vista previa y descargar

4. **Validar seguridad**
   - Intentar acceso como alumno a páginas de profesor
   - Debe mostrar "Acceso denegado"

### Checklist Completo
Ver [GUIA_TESTING.md](GUIA_TESTING.md) para lista completa de testing

---

## 🛡️ Seguridad

### Implementado ✅
- Autenticación con sesiones
- Validación de rol en cada página
- Prepared statements (SQL safe)
- HTML escaping (XSS prevention)
- Validación de entrada

### Recomendaciones para Producción ⚠️
- Usar HTTPS obligatorio
- Hash de contraseñas con password_hash()
- Confirmación de email en registro
- Rate limiting en login
- CSRF tokens en formularios
- Auditoría de exportaciones
- Logs de cambios

---

## 🐛 Solución de Problemas

### "Error de conexión a BD"
```
✓ Verifica MySQL está corriendo
✓ Verifica credenciales en conexion.php
✓ Verifica BD kronno2026 existe
```

### "Gráficos no cargan"
```
✓ Abre consola (F12) y busca errores JS
✓ Verifica Chart.js CDN está disponible
✓ Recarga la página
```

### "Búsqueda no funciona"
```
✓ Verifica que hay alumnos en BD
✓ Intenta búsqueda simple
✓ Limpia caché del navegador
```

### "Solo veo 'Acceso denegado'"
```
✓ Verifica que eres profesor (no alumno)
✓ Cierra sesión y login nuevamente
✓ Limpia cookies del navegador
```

Ver [GUIA_TESTING.md](GUIA_TESTING.md) para más soluciones.

---

## 📝 Archivos Clave

### Configuración
- `conexion.php` - Conexión a MySQL
- `config.php` - Funciones globales
- `database.sql` - Estructura y datos

### Autenticación
- `auth.php` - Funciones de autenticación
- `login.php` - Formulario de login
- `logout.php` - Cierre de sesión
- `registro-*.php` - Registro por rol

### Features
- `dashboard.php` - Dashboard con gráficos
- `alumnos.php` - Búsqueda avanzada
- `exportar.php` - Exportación de reportes
- `lib/charts.php` - Datos para gráficos
- `lib/export.php` - Funciones de exportación

### Navegación
- `header.php` - Menú principal
- `footer.php` - Pie de página
- `index.php` - Página de inicio

---

## 🚀 Próximos Pasos

### Mejoras Recomendadas
1. **Permisos granulares** - Algunos profesores solo ven sus cursos
2. **PDF export** - Además de CSV
3. **Alertas automáticas** - Email cuando asistencia cae < 80%
4. **Auditoría** - Registrar cambios de usuarios
5. **App móvil** - Aplicación nativa iOS/Android
6. **2FA** - Autenticación de dos factores

### Integración con Otros Sistemas
- Sincronizar con Google Classroom
- Integración con WhatsApp para notificaciones
- API REST para aplicaciones externas
- Importación desde Excel

---

## 📞 Soporte

### Información del Sistema
- **Desarrollador:** Eliot Daniel Balbuena Céspedes
- **Institución:** Colegio KRONO 2026
- **Versión:** 2.0 (Completa con Top 3 Features)
- **Última actualización:** Noviembre 2026

### Preguntas Frecuentes
Ver [GUIA_TESTING.md](GUIA_TESTING.md) sección "Solución de Problemas"

---

## 📄 Licencia

Sistema desarrollado internamente para Colegio KRONO 2026.
Uso exclusivo dentro de la institución.

---

## ✅ Checklist de Deployment

Antes de usar en producción:

- [ ] Base de datos configurada y poblada
- [ ] Conexión a MySQL verificada
- [ ] HTTPS configurado
- [ ] Contraseñas de BD cambiadas
- [ ] Sistema de backups implementado
- [ ] Logs configurados
- [ ] Testing completado (ver GUIA_TESTING.md)
- [ ] Emails configurados (opcional)
- [ ] Capacitación de usuarios completada

---

**¡Sistema listo para usar!** 🎉

Para empezar: ve a [GUIA_TESTING.md](GUIA_TESTING.md) y sigue los pasos de testing.

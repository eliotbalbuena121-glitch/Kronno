# Guía de Testing - Sistema KRONO 2026

## Requisitos Previos

Asegúrate de tener:
- ✅ MySQL/MariaDB corriendo
- ✅ Base de datos `kronno2026` creada con `database.sql`
- ✅ Servidor web (Apache, Nginx) con PHP 7.4+
- ✅ Extensión PDO de MySQL habilitada

## Antes de Empezar

### 1. Crear Base de Datos

```sql
-- Ejecuta database.sql en MySQL
mysql -u root < database.sql
```

Esto creará:
- Base de datos `kronno2026`
- 6 tablas: cursos, profesores, alumnos, asistencias, justificativos, usuarios
- Datos de prueba precargados

### 2. Configurar Conexión

Edita `conexion.php`:

```php
$servidor = "localhost";
$usuario = "root";        // Tu usuario MySQL
$password = "";           // Tu contraseña (vacío para localhost)
$base_datos = "kronno2026";
```

## Escenarios de Testing

### Escenario 1: Crear Cuentas de Prueba

**Objetivo:** Verificar que el registro funciona

#### Paso 1: Registrar Profesor

1. Abre `http://localhost/KRONNO/registro-profesor.php`
2. Completa:
   - Email: `profe@test.com`
   - Contraseña: `123456`
   - Nombre: `Profesor Test`
3. Haz clic en "Registrar"
4. Deberías ver: "Registro exitoso. Puedes iniciar sesión."

#### Paso 2: Registrar Alumno

1. Abre `http://localhost/KRONNO/registro-alumno.php`
2. Completa:
   - Email: `alumno@test.com`
   - Contraseña: `123456`
   - Nombre: `Alumno Test`
3. Haz clic en "Registrar"

---

### Escenario 2: Login y Navegación

**Objetivo:** Verificar que roles funcionan correctamente

#### Paso 1: Login como Profesor

1. Abre `http://localhost/KRONNO/login.php`
2. Email: `profe@test.com` | Contraseña: `123456`
3. Haz clic en "Iniciar Sesión"

**Esperado:**
- ✅ Redirige a `index.php`
- ✅ Menú muestra opciones de profesor
- ✅ Ves links: Dashboard, Alumnos, Cursos, Profesores, etc.
- ✅ Nombre del usuario en top-right

#### Paso 2: Verificar Links del Menú

Haz clic en cada link y verifica que carga:
- ✅ Dashboard → Muestra gráficos
- ✅ Alumnos → Muestra tabla con búsqueda
- ✅ Exportar → Muestra formulario de exportación
- ✅ Otros → Cargan sin errores

#### Paso 3: Login como Alumno

1. Cierra sesión (botón en top-right)
2. Abre login nuevamente
3. Email: `alumno@test.com` | Contraseña: `123456`

**Esperado:**
- ✅ Redirige a `index.php`
- ✅ Menú muestra solo opciones de alumno:
  - Inicio
  - Mis Asistencias
  - Mis Justificativos
- ✅ NO ve links de Dashboard, Exportar, Alumnos, etc.

#### Paso 4: Intentar Acceso Directo (Seguridad)

Como alumno, abre directamente:
- `http://localhost/KRONNO/dashboard.php`

**Esperado:**
- ✅ Muestra error: "Acceso denegado - Solo los profesores..."

---

### Escenario 3: Feature #1 - Dashboard 📊

**URL:** `http://localhost/KRONNO/dashboard.php`

**Pasos de Testing:**

1. **Verificar Tarjetas de Estadísticas:**
   - [ ] Total Alumnos: debe mostrar número
   - [ ] Total Cursos: debe mostrar número
   - [ ] Profesores: debe mostrar número
   - [ ] Hoy: debe mostrar número (probablemente 0 si no hay datos)

2. **Verificar Gráfico de Línea (Asistencias):**
   - [ ] Carga el gráfico (tarda 1-2 segundos)
   - [ ] Muestra líneas de 3 colores: Verde (Presentes), Rojo (Ausentes), Amarillo (Tardanzas)
   - [ ] Eje X muestra fechas
   - [ ] Eje Y muestra cantidades
   - [ ] Pasa mouse sobre líneas y muestra tooltips

3. **Verificar Gráfico de Pastel (Distribución):**
   - [ ] Carga el gráfico
   - [ ] Muestra 3 secciones de colores
   - [ ] Leyenda abajo con: Presente, Ausente, Tardanza
   - [ ] Pasa mouse y muestra porcentajes

4. **Verificar Tabla de Cursos:**
   - [ ] Muestra lista de cursos
   - [ ] Cada fila tiene: Curso | Alumnos | Presentes | Ausentes | Tardanzas | % Asistencia
   - [ ] Barra de progreso en columna % Asistencia
   - [ ] Badges de color (verde presentes, rojo ausentes, amarillo tardanzas)

5. **Verificar Tabla de Alumnos Críticos:**
   - [ ] Si no hay: "Excelente! No hay alumnos con asistencia crítica"
   - [ ] Si hay: muestra tabla con fondo rojo
   - [ ] Columnas: Alumno | Documento | Curso | Registros | Presentes | % Asistencia
   - [ ] Alumnos con < 80% de asistencia

---

### Escenario 4: Feature #2 - Búsqueda Avanzada 🔍

**URL:** `http://localhost/KRONNO/alumnos.php`

**Pasos de Testing:**

1. **Búsqueda por Nombre:**
   - [ ] Escribe "Eliot" en el campo de búsqueda
   - [ ] Presiona Enter o espera 1 segundo
   - [ ] Filtra la tabla mostrando solo coincidencias
   - [ ] Contador muestra "X registros"
   - [ ] Haz clic en X para limpiar búsqueda

2. **Búsqueda por Documento:**
   - [ ] Escribe "A-102" en búsqueda
   - [ ] Filtra por documento

3. **Búsqueda por Email:**
   - [ ] Escribe "correo.com" en búsqueda
   - [ ] Filtra por email

4. **Filtro por Curso:**
   - [ ] Selecciona un curso del dropdown
   - [ ] Tabla se filtra mostrando solo alumnos de ese curso
   - [ ] Combina con búsqueda

5. **Ordenamiento:**
   - [ ] Haz clic en encabezado "Nombre"
   - [ ] Tabla ordena por nombre (A-Z)
   - [ ] Haz clic nuevamente (ordena Z-A)
   - [ ] Mismo para: ID, Documento, Curso

6. **Agregar Alumno:**
   - [ ] Haz clic en botón "Nuevo Alumno"
   - [ ] Modal se abre
   - [ ] Completa:
     - Nombre: `Test Alumno Nuevo`
     - Documento: `T-9999`
     - Curso: Selecciona uno
     - Email: `test@nuevo.com`
     - Teléfono: `0981123456`
   - [ ] Haz clic "Guardar"
   - [ ] Modal cierra y alumno aparece en tabla

---

### Escenario 5: Feature #3 - Exportar Reportes 📥

**URL:** `http://localhost/KRONNO/exportar.php`

#### Test 5A: Exportar Asistencias

1. **Vista Previa:**
   - [ ] Tipo: Asistencias (seleccionado por defecto)
   - [ ] Curso: Todos (vacío)
   - [ ] Fecha Desde: (vacío)
   - [ ] Fecha Hasta: (vacío)
   - [ ] Haz clic: "Vista Previa"
   - [ ] Tabla muestra primeros 20 registros
   - [ ] Columnas: Nombre | Documento | Curso | Fecha | Estado | Observación

2. **Filtrar por Curso:**
   - [ ] Selecciona un curso
   - [ ] Haz clic "Vista Previa"
   - [ ] Filtra registros de ese curso

3. **Filtrar por Fechas:**
   - [ ] Fecha Desde: `2026-08-18`
   - [ ] Fecha Hasta: `2026-08-19`
   - [ ] Haz clic "Vista Previa"
   - [ ] Muestra solo registros en ese rango

4. **Descargar CSV:**
   - [ ] Haz clic "Descargar CSV"
   - [ ] Descarga archivo: `asistencias_YYYY-MM-DD.csv`
   - [ ] Abre en Excel/LibreOffice
   - [ ] Verifica datos con `;` como separador

#### Test 5B: Exportar Justificativos

1. **Cambiar Tipo:**
   - [ ] Selecciona "Justificativos"
   - [ ] Filtros cambian (mostrar "Estado" en lugar de "Fechas")
   - [ ] Haz clic "Vista Previa"

2. **Filtrar por Estado:**
   - [ ] Estado: "Pendiente"
   - [ ] Haz clic "Vista Previa"
   - [ ] Muestra solo justificativos pendientes

#### Test 5C: Exportar Alumnos

1. **Cambiar Tipo:**
   - [ ] Selecciona "Alumnos"
   - [ ] Filtro "Estado" desaparece
   - [ ] Solo queda filtro de Curso

2. **Descargar:**
   - [ ] Curso: (todos)
   - [ ] Haz clic "Descargar CSV"
   - [ ] Descarga: `alumnos_YYYY-MM-DD.csv`

---

## Checklist de Validación Final

### Funcionalidad

- [ ] Dashboard carga sin errores
- [ ] Gráficos renderean correctamente
- [ ] Búsqueda filtra datos en tiempo real
- [ ] Ordenamiento funciona en todas las columnas
- [ ] Exportación genera CSV válido
- [ ] Vista previa muestra 20 registros máximo

### Seguridad

- [ ] Solo profesores pueden ver Dashboard
- [ ] Solo profesores pueden ver Exportar
- [ ] Alumnos reciben error al intentar acceso directo
- [ ] Contraseñas no se muestran en logs
- [ ] SQL injection no funciona (prepared statements)
- [ ] XSS no funciona (HTML escapado)

### Interfaz

- [ ] Menú responsive en móvil
- [ ] Iconos se muestran correctamente
- [ ] Colores y badges son visibles
- [ ] Tablas son legibles en móvil
- [ ] Botones son clickeables

### Base de Datos

- [ ] Datos se guardan correctamente
- [ ] No hay errores de conexión
- [ ] Columnas correctas (no 'apellido', 'cedula', 'nombre_curso')
- [ ] Queries retornan resultados esperados

---

## Solución de Problemas

### "Error de conexión"
- [ ] Verifica MySQL está corriendo
- [ ] Revisa `conexion.php` con credenciales correctas
- [ ] Verifica BD `kronno2026` existe

### "Acceso denegado"
- [ ] Verifica que eres profesor (no alumno)
- [ ] Intenta cerrar sesión y login nuevamente
- [ ] Limpia cookies del navegador

### "Gráficos no cargan"
- [ ] Abre consola del navegador (F12)
- [ ] Busca errores de JS
- [ ] Verifica que Chart.js CDN está disponible

### "Búsqueda no funciona"
- [ ] Recarga la página (F5)
- [ ] Verifica que hay alumnos en BD
- [ ] Intenta búsqueda simple (ej: "a")

### "CSV no descarga"
- [ ] Verifica pop-ups no están bloqueados
- [ ] Intenta en otra pestaña
- [ ] Comprueba que hay datos (usa Vista Previa primero)

---

## Datos de Prueba

La BD viene precargada con:

**Profesores:** (10 profesores)
- Email: `eliot@krono.edu.py`, `giovanna@krono.edu.py`, etc.
- Contraseña: (requiere registro nuevo)

**Alumnos:** (10 alumnos)
- Asignados a varios cursos
- Con datos de asistencia y justificativos

**Cursos:** (10 cursos)
- Desde 1ro BTI hasta 6to Grado
- Turnos Mañana/Tarde

**Asistencias:** (10 registros de 2026-08-18)
- Estados: Presente, Ausente, Tardanza
- Con observaciones

**Justificativos:** (10 registros)
- Estados: Pendiente, Aprobado, Rechazado
- Con motivos

---

## Notas Importantes

⚠️ **En Desarrollo:**
- Las contraseñas se aceptan sin cifrar (para testing)
- No hay validación de email en registro
- Cambiar a producción requiere:
  - Usar `password_hash()` para contraseñas
  - Confirmar email
  - Agregar CSRF tokens

📝 **Performance:**
- Dashboard carga todos los gráficos (puede tomar 1-2 segundos)
- Búsqueda es real-time pero puede retardar con 1000+ registros
- CSV se genera en memoria (ok para <100K registros)

🔐 **Seguridad:**
- Cambiar credenciales de BD en producción
- Usar HTTPS en producción
- Agregar rate limiting en login
- Auditoría de exportaciones

---

## Contacto

¿Problemas? Revisa:
1. `IMPLEMENTACION.md` - Detalles técnicos
2. `ROLES_Y_PERMISOS.md` - Permisos y acceso
3. `database.sql` - Estructura de BD
4. Consola de navegador (F12) - Errores JS
5. Logs de servidor - Errores PHP

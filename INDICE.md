# 📑 Índice de Documentación - KRONO 2026 v2.0

Guía rápida para navegar toda la documentación del proyecto.

---

## 🚀 Comienza Aquí

### Si es tu primera vez:
1. **[README.md](README.md)** ← START HERE
   - Descripción del proyecto
   - Requisitos y instalación
   - Visión general de features

### Si necesitas probar:
2. **[GUIA_TESTING.md](GUIA_TESTING.md)** ← TESTING MANUAL
   - Escenarios de prueba paso a paso
   - Datos de prueba precargados
   - Solución de problemas
   - Checklist de validación

### Si necesitas entender la implementación:
3. **[IMPLEMENTACION.md](IMPLEMENTACION.md)** ← DETALLES TÉCNICOS
   - Features detallados
   - Funciones implementadas
   - Consideraciones de seguridad
   - Notas técnicas

---

## 📊 Mapa de Features

### Feature 1: Dashboard 📊
**Archivo:** `dashboard.php`

**Documentación:**
- Descripción: [IMPLEMENTACION.md → Feature 1](IMPLEMENTACION.md)
- Testing: [GUIA_TESTING.md → Escenario 3](GUIA_TESTING.md)
- Cambios: [CAMBIOS_v2.md → Feature 1](CAMBIOS_v2.md)

**Código:**
- Principal: `dashboard.php` (280 líneas)
- Funciones: `lib/charts.php` (5 funciones)

---

### Feature 2: Búsqueda Avanzada 🔍
**Archivo:** `alumnos.php`

**Documentación:**
- Descripción: [IMPLEMENTACION.md → Feature 2](IMPLEMENTACION.md)
- Testing: [GUIA_TESTING.md → Escenario 4](GUIA_TESTING.md)
- Cambios: [CAMBIOS_v2.md → Archivo alumnos.php](CAMBIOS_v2.md)

**Código:**
- Principal: `alumnos.php` (200 líneas - reescrito)

---

### Feature 3: Exportar Reportes 📥
**Archivo:** `exportar.php`

**Documentación:**
- Descripción: [IMPLEMENTACION.md → Feature 3](IMPLEMENTACION.md)
- Testing: [GUIA_TESTING.md → Escenario 5](GUIA_TESTING.md)
- Cambios: [CAMBIOS_v2.md → Feature 3](CAMBIOS_v2.md)

**Código:**
- Principal: `exportar.php` (220 líneas)
- Funciones: `lib/export.php` (5 funciones)

---

## 🗂️ Documentos Completos

### Por Propósito

#### 📖 Introducción
- [README.md](README.md) - Guía principal y comienza aquí
- [INDICE.md](INDICE.md) - Este documento (navegación)

#### 🧪 Testing
- [GUIA_TESTING.md](GUIA_TESTING.md) - 5 escenarios completos con pasos detallados

#### 🔧 Técnico
- [IMPLEMENTACION.md](IMPLEMENTACION.md) - Features, funciones, seguridad
- [CAMBIOS_v2.md](CAMBIOS_v2.md) - Registro detallado de cambios
- [database.sql](database.sql) - Estructura de BD y datos

#### 👥 Control de Acceso
- [ROLES_Y_PERMISOS.md](ROLES_Y_PERMISOS.md) - Permisos por rol y acceso
- [VERIFICACION.md](VERIFICACION.md) - Checklist de verificación

---

## 📋 Documentos por Tipo

### Introducción (Leer Primero)
1. [README.md](README.md)
   - Stack tecnológico
   - Inicio rápido
   - Requisitos

2. [INDICE.md](INDICE.md) ← Estás aquí
   - Navegación de docs
   - Mapa de contenidos

### Información Detallada
3. [IMPLEMENTACION.md](IMPLEMENTACION.md)
   - Descripción de features
   - Archivos creados/modificados
   - Funciones implementadas
   - Seguridad

4. [CAMBIOS_v2.md](CAMBIOS_v2.md)
   - Archivo por archivo
   - Antes/después
   - Impacto de cambios
   - Estadísticas

### Testing y Validación
5. [GUIA_TESTING.md](GUIA_TESTING.md)
   - 5 escenarios de testing
   - Pasos detallados
   - Checklist de validación
   - Solución de problemas

6. [VERIFICACION.md](VERIFICACION.md)
   - Checklist de archivos
   - Validación de contenido
   - Status de implementación

### Control de Acceso
7. [ROLES_Y_PERMISOS.md](ROLES_Y_PERMISOS.md)
   - Permisos por rol
   - Acceso a páginas

### Base de Datos
8. [database.sql](database.sql)
   - Estructura de tablas
   - Datos de prueba

---

## 🎯 Busco... (Índice por Necesidad)

### "¿Por dónde empiezo?"
→ [README.md](README.md) + [INDICE.md](INDICE.md)

### "¿Cómo instalo el sistema?"
→ [README.md](README.md) → Sección "Inicio Rápido"

### "¿Cómo pruebo las features?"
→ [GUIA_TESTING.md](GUIA_TESTING.md)

### "¿Qué archivos fueron creados/modificados?"
→ [CAMBIOS_v2.md](CAMBIOS_v2.md)

### "¿Cuáles son los detalles técnicos?"
→ [IMPLEMENTACION.md](IMPLEMENTACION.md)

### "¿Quién puede acceder a qué?"
→ [ROLES_Y_PERMISOS.md](ROLES_Y_PERMISOS.md)

### "¿Cómo es la estructura de la BD?"
→ [database.sql](database.sql)

### "¿Está todo implementado correctamente?"
→ [VERIFICACION.md](VERIFICACION.md)

### "¿Hay problemas?"
→ [GUIA_TESTING.md](GUIA_TESTING.md) → Sección "Solución de Problemas"

---

## 📂 Estructura de Carpetas

```
KRONNO/
├── 📄 DOCUMENTACION
│   ├── README.md                ← Comienza aquí
│   ├── INDICE.md                ← Este archivo
│   ├── GUIA_TESTING.md          ← Testing paso a paso
│   ├── IMPLEMENTACION.md        ← Detalles técnicos
│   ├── CAMBIOS_v2.md            ← Registro de cambios
│   ├── VERIFICACION.md          ← Checklist
│   ├── ROLES_Y_PERMISOS.md      ← Control de acceso
│   └── database.sql             ← BD y datos
│
├── 🆕 FEATURES NUEVOS
│   ├── dashboard.php            ← Dashboard con gráficos
│   ├── exportar.php             ← Exportar reportes
│   ├── lib/
│   │   ├── charts.php           ← Datos para gráficos
│   │   └── export.php           ← Funciones de exportación
│   └── alumnos.php              ← Búsqueda avanzada (modificado)
│
├── 🔧 SISTEMA BASE
│   ├── header.php               ← Navegación (modificado)
│   ├── footer.php               ← Pie de página
│   ├── index.php                ← Página de inicio
│   ├── login.php                ← Login
│   ├── logout.php               ← Logout
│   ├── auth.php                 ← Autenticación
│   ├── conexion.php             ← Conexión a BD
│   ├── config.php               ← Configuración
│   └── [otros archivos]         ← Reportes, asistencias, etc.
│
└── 📁 OTROS
    ├── public/                  ← Imágenes
    ├── app/                     ← Componentes Next.js (no usado)
    └── lib/
        └── utils.ts             ← Utilidades TypeScript
```

---

## 🔄 Flujo de Lectura Recomendado

### Opción 1: Nuevo Usuario (Uso Normal)
```
1. README.md           (5 min)
   ↓
2. GUIA_TESTING.md     (30 min - testing)
   ↓
3. Usar el sistema     ✓
```

### Opción 2: Desarrollador (Implementación)
```
1. README.md            (5 min)
   ↓
2. IMPLEMENTACION.md    (15 min)
   ↓
3. CAMBIOS_v2.md        (10 min)
   ↓
4. Revisar código       (20 min)
   ↓
5. VERIFICACION.md      (5 min)
```

### Opción 3: Administrador (Deployment)
```
1. README.md                    (5 min)
   ↓
2. CAMBIOS_v2.md → Deployment   (5 min)
   ↓
3. GUIA_TESTING.md → Testing    (30 min)
   ↓
4. VERIFICACION.md → Validar    (10 min)
   ↓
5. Producción                   ✓
```

---

## 📊 Referencias Rápidas

### Funciones Clave

**lib/charts.php (Gráficos):**
- `get_asistencia_por_fecha()` → Datos para gráfico de línea
- `get_distribucion_asistencias()` → Datos para gráfico de pastel
- `get_alumnos_asistencia_critica()` → Alumnos con baja asistencia
- `get_estadisticas_generales()` → Tarjetas de estadísticas
- `get_asistencia_por_curso()` → Tabla de cursos

**lib/export.php (Exportación):**
- `export_asistencias_csv()` → Exporta asistencias
- `export_justificativos_csv()` → Exporta justificativos
- `export_alumnos_csv()` → Exporta alumnos
- `download_csv()` → Descarga archivo
- `generate_reporte_html()` → Preview HTML

### URLs de Acceso

**Admin/Profesor:**
- `index.php` → Página de inicio
- `dashboard.php` → Dashboard con gráficos
- `alumnos.php` → Búsqueda avanzada
- `exportar.php` → Exportar reportes
- `cursos.php` → Gestión de cursos
- `profesores.php` → Gestión de profesores
- `asistencias.php` → Registrar asistencias
- `justificativos.php` → Gestionar justificativos

**Alumno:**
- `index.php` → Página de inicio
- `mis-asistencias.php` → Mis asistencias
- `mis-justificativos.php` → Mis justificativos
- `perfil.php` → Mi perfil

**General:**
- `login.php` → Login
- `logout.php` → Logout
- `registro-profesor.php` → Registrar profesor
- `registro-alumno.php` → Registrar alumno

---

## 🔐 Seguridad

Para información sobre seguridad, ver:
- [IMPLEMENTACION.md → Seguridad](IMPLEMENTACION.md)
- [ROLES_Y_PERMISOS.md](ROLES_Y_PERMISOS.md)
- [GUIA_TESTING.md → Solución de Problemas](GUIA_TESTING.md)

---

## 🚀 Próximos Pasos

### Inmediato
1. Leer [README.md](README.md)
2. Hacer testing con [GUIA_TESTING.md](GUIA_TESTING.md)

### Corto Plazo
3. Capacitar usuarios
4. Ir a producción

### Futuro
5. Ver sugerencias en [CAMBIOS_v2.md → Próximas versiones](CAMBIOS_v2.md)

---

## 📞 Soporte

**¿Problema con instalación?**
→ [README.md → Inicio Rápido](README.md)

**¿Problema con testing?**
→ [GUIA_TESTING.md → Solución de Problemas](GUIA_TESTING.md)

**¿Pregunta técnica?**
→ [IMPLEMENTACION.md](IMPLEMENTACION.md)

**¿Qué cambió?**
→ [CAMBIOS_v2.md](CAMBIOS_v2.md)

---

## ✅ Checklist Rápido

- [ ] He leído README.md
- [ ] He revisado GUIA_TESTING.md
- [ ] He probado Dashboard
- [ ] He probado Búsqueda
- [ ] He probado Exportar
- [ ] He revisado IMPLEMENTACION.md
- [ ] Estoy listo para producción

---

## 📊 Estadísticas de Documentación

| Documento | Páginas | Secciones | Actualización |
|-----------|---------|-----------|--------------|
| README.md | ~2 | 12 | Completo |
| GUIA_TESTING.md | ~5 | 15 | Completo |
| IMPLEMENTACION.md | ~3 | 12 | Completo |
| CAMBIOS_v2.md | ~4 | 14 | Completo |
| VERIFICACION.md | ~2 | 10 | Completo |
| INDICE.md | ~2 | 10 | Completo |
| ROLES_Y_PERMISOS.md | ~1 | 5 | Existente |
| database.sql | ~1 | N/A | Existente |

**Total: ~18 páginas, 78 secciones**

---

## 🎯 Tu Punto de Partida

**¿Por dónde empiezo?** 👇

→ **[Abre README.md](README.md)**

---

*Índice v2.0 - Noviembre 2026*  
*Última actualización: Noviembre 2026*  
*Sistema: KRONO 2026*

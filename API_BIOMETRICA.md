# API Biométrica - KRONO 2026

## 🎯 Descripción

API REST para integración de autenticación biométrica (huella digital) con el sistema de asistencias KRONO 2026.

**URL Base:** `https://tudominio.com/api/biometric.php`

**Método:** HTTP POST/GET

**Content-Type:** `application/json`

---

## 🔐 Seguridad

- ✅ HTTPS requerido en producción
- ✅ Tokens de corta duración (5 minutos)
- ✅ Validación de alumno activo
- ✅ Bloqueo después de 5 intentos fallidos
- ✅ Auditoría de todos los intentos
- ✅ IP logging para seguridad

---

## 📋 Endpoints

### 1️⃣ INICIAR REGISTRO DE HUELLA

**URL:** `POST /api/biometric.php?action=register-start`

**Descripción:** Genera un token único para que el alumno registre su huella.

**Body (JSON):**
```json
{
  "alumno_id": 1,
  "dispositivo_id": "device_uuid_from_phone"
}
```

**Parámetros:**
- `alumno_id` (int, requerido): ID del alumno en la BD
- `dispositivo_id` (string, opcional): ID único del dispositivo móvil

**Response (Éxito):**
```json
{
  "success": true,
  "message": "Registro iniciado. Prepárate para capturar tu huella.",
  "data": {
    "token": "a1b2c3d4e5f6g7h8...",
    "codigo_registro": "ABC-1234-XYZ",
    "expira_en_segundos": 300,
    "alumno_nombre": "Carlos Méndez"
  },
  "timestamp": "2026-11-08T10:30:45+00:00"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Alumno no encontrado o inactivo",
  "timestamp": "2026-11-08T10:30:45+00:00"
}
```

**Ejemplo con cURL:**
```bash
curl -X POST "https://tudominio.com/api/biometric.php?action=register-start" \
  -H "Content-Type: application/json" \
  -d '{
    "alumno_id": 1,
    "dispositivo_id": "a1b2c3d4-e5f6-g7h8-i9j0"
  }'
```

---

### 2️⃣ CONFIRMAR REGISTRO DE HUELLA

**URL:** `POST /api/biometric.php?action=register-confirm`

**Descripción:** Guarda la huella digital capturada por el alumno.

**Body (JSON):**
```json
{
  "token": "a1b2c3d4e5f6g7h8...",
  "huella_base64": "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==",
  "dispositivo_id": "device_uuid_from_phone"
}
```

**Parámetros:**
- `token` (string, requerido): Token del registro iniciado
- `huella_base64` (string, requerido): Datos de huella en Base64
- `dispositivo_id` (string, opcional): ID del dispositivo

**Response (Éxito):**
```json
{
  "success": true,
  "message": "Huella registrada exitosamente",
  "data": {
    "registro_id": 42,
    "mensaje": "✅ Tu huella ha sido registrada exitosamente. Ya puedes usar la app para marcar asistencia."
  },
  "timestamp": "2026-11-08T10:31:15+00:00"
}
```

**Response (Error):**
```json
{
  "success": false,
  "message": "Token inválido: Token expirado",
  "timestamp": "2026-11-08T10:35:45+00:00"
}
```

---

### 3️⃣ AUTENTICAR Y MARCAR ASISTENCIA

**URL:** `POST /api/biometric.php?action=authenticate`

**Descripción:** Autentica por huella digital y registra automáticamente la asistencia.

**Body (JSON):**
```json
{
  "alumno_codigo": "A-1001",
  "huella_base64": "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==",
  "dispositivo_id": "device_uuid_from_phone"
}
```

**Parámetros:**
- `alumno_codigo` (string, requerido): Documento del alumno (ej: A-1001)
- `huella_base64` (string, requerido): Datos de huella en Base64
- `dispositivo_id` (string, opcional): ID del dispositivo

**Response (Éxito):**
```json
{
  "success": true,
  "message": "Asistencia registrada correctamente",
  "data": {
    "asistencia_id": 1234,
    "alumno_nombre": "Carlos Méndez",
    "alumno_id": 1,
    "hora": "08:30:45",
    "estado": "Presente",
    "curso": "1ro BTI",
    "fecha": "2026-11-08",
    "mensaje": "✅ ¡Asistencia registrada exitosamente!"
  },
  "timestamp": "2026-11-08T08:30:45+00:00"
}
```

**Response (Error - Huella no coincide):**
```json
{
  "success": false,
  "message": "La huella no coincide. Intenta de nuevo.",
  "timestamp": "2026-11-08T08:31:00+00:00"
}
```

**Response (Error - Cuenta bloqueada):**
```json
{
  "success": false,
  "message": "Tu cuenta ha sido bloqueada por demasiados intentos fallidos",
  "timestamp": "2026-11-08T08:35:00+00:00"
}
```

---

### 4️⃣ VERIFICAR ESTADO DE TOKEN

**URL:** `GET /api/biometric.php?action=status&token=XXX`

**Descripción:** Verifica si un token es válido y cuánto tiempo queda.

**Parámetros Query:**
- `token` (string, requerido): Token a verificar

**Response (Token válido):**
```json
{
  "success": true,
  "data": {
    "token_valido": true,
    "estado": "activo",
    "codigo_registro": "ABC-1234-XYZ",
    "expira_en_segundos": 250,
    "alumno_id": 1
  },
  "timestamp": "2026-11-08T10:31:10+00:00"
}
```

**Response (Token inválido):**
```json
{
  "success": true,
  "data": {
    "token_valido": false,
    "razon": "Token expirado"
  },
  "timestamp": "2026-11-08T10:35:50+00:00"
}
```

---

## 🔄 Flujo Completo de Registro

```
1. APP MÓVIL:
   POST /register-start
   {alumno_id: 1, dispositivo_id: "xxx"}
   ↓
2. SERVIDOR RESPONDE:
   {token: "abc123", codigo_registro: "ABC-1234-XYZ"}
   ↓
3. APP MÓVIL:
   - Muestra: "Tu código: ABC-1234-XYZ"
   - Pide al alumno capturar huella
   ↓
4. APP MÓVIL:
   POST /register-confirm
   {token: "abc123", huella_base64: "iVBORw0..."}
   ↓
5. SERVIDOR RESPONDE:
   {success: true, registro_id: 42}
   ↓
6. ALUMNO VE:
   ✅ Tu huella ha sido registrada
```

---

## 🔄 Flujo de Marcación de Asistencia

```
1. APP MÓVIL:
   - Alumno coloca dedo en sensor
   ↓
2. APP MÓVIL:
   POST /authenticate
   {alumno_codigo: "A-1001", huella_base64: "iVBORw0..."}
   ↓
3. SERVIDOR:
   - Busca al alumno por documento
   - Compara huella con la registrada
   - Si coincide → CREA asistencia en BD
   ↓
4. SERVIDOR RESPONDE:
   {
     success: true,
     asistencia_id: 1234,
     alumno_nombre: "Carlos",
     hora: "08:30:45"
   }
   ↓
5. APP MÓVIL MUESTRA:
   ✅ Asistencia registrada - 08:30 - Presente
   ↓
6. ADMIN VE EN WEB:
   - Menú Asistencias → 1ro BTI → Carlos Méndez → Presente 08:30
```

---

## 📱 Ejemplo de Implementación (React Native)

```javascript
// biometric.service.js
const API_BASE = 'https://tudominio.com/api/biometric.php';

// 1. Iniciar registro
export const registerStart = async (alumnoId, dispositivoId) => {
  const response = await fetch(`${API_BASE}?action=register-start`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      alumno_id: alumnoId,
      dispositivo_id: dispositivoId
    })
  });
  return await response.json();
};

// 2. Confirmar registro (después de capturar huella)
export const registerConfirm = async (token, huellaBas64, dispositivoId) => {
  const response = await fetch(`${API_BASE}?action=register-confirm`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      token,
      huella_base64: huellaBas64,
      dispositivo_id: dispositivoId
    })
  });
  return await response.json();
};

// 3. Marcar asistencia por huella
export const authenticate = async (alumnoCodigo, huellaBas64, dispositivoId) => {
  const response = await fetch(`${API_BASE}?action=authenticate`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      alumno_codigo: alumnoCodigo,
      huella_base64: huellaBas64,
      dispositivo_id: dispositivoId
    })
  });
  return await response.json();
};
```

---

## ⚠️ Códigos de Error

| Código | Mensaje | Causa |
|--------|---------|-------|
| 400 | Token no especificado | Falta parámetro requerido |
| 401 | La huella no coincide | Biometría no valida |
| 403 | Tu cuenta ha sido bloqueada | 5+ intentos fallidos |
| 404 | Alumno no encontrado | ID/documento inválido |
| 405 | Método no permitido | GET en endpoint POST |
| 500 | Error al autenticar | Error interno del servidor |

---

## 🛡️ Seguridad y Buenas Prácticas

✅ **DO:**
- Usar HTTPS en producción
- Validar todos los inputs
- Capturar excepciones
- Hacer reintentos con backoff
- Guardar dispositivo_id para auditoría

❌ **DON'T:**
- Enviar tokens en URL (GET)
- Guardar contraseñas en plain text
- Ignorar errores de API
- Usar HTTP en producción
- Confiar ciegamente en biometría

---

## 📊 Auditoría

Todos los intentos (exitosos o fallidos) se registran en tabla `biometric_attempts`:

```sql
SELECT * FROM biometric_attempts
WHERE alumno_id = 1
ORDER BY timestamp DESC
LIMIT 10;
```

**Campos registrados:**
- alumno_id
- tipo (registro, autenticacion, asistencia)
- resultado (exitoso, fallido, error)
- razon_fallo
- timestamp
- ip_address
- dispositivo_id

---

## 🚀 Próximos Pasos

1. **Local:** Prueba endpoints con Postman o cURL
2. **Deploy:** Sube a Hostinger
3. **Móvil:** Conecta app React Native/Flutter
4. **Testing:** Registra alumno de prueba
5. **Producción:** Activa para todos los alumnos

---

## 📞 Soporte

¿Problemas? Revisa:
1. Que la BD tiene tablas biométricas
2. Que `.env` tiene datos correctos
3. Logs de `biometric_attempts`
4. Respuestas JSON de la API

---

**Versión:** 1.0  
**Última actualización:** 2026-11-08  
**Desarrollado por:** Eliot + Kiro


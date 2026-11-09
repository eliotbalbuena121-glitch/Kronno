# API Biométrica - KRONO 2026

## 📁 Contenido de la Carpeta

```
api/
├── response.php .................... Clase para respuestas JSON estándar
├── biometric_utils.php ............ Utilidades (tokens, hashes, logs)
├── biometric.php .................. API PRINCIPAL (4 endpoints)
├── test_biometric.sh .............. Script de pruebas con cURL
└── README.md ...................... Este archivo
```

---

## 🚀 Inicio Rápido

### 1. Asegúrate de que `database.sql` incluya tablas biométricas:

```sql
✅ biometric_tokens
✅ biometric_registrations
✅ biometric_attempts
```

### 2. Acceder a los endpoints:

**URL Base:** `https://tudominio.com/api/biometric.php`

**Acciones disponibles:**
- `?action=register-start` → Iniciar registro de huella
- `?action=register-confirm` → Confirmar y guardar huella
- `?action=authenticate` → Autenticar y marcar asistencia
- `?action=status&token=XXX` → Verificar estado de token

### 3. Documentación completa:

Ver `../API_BIOMETRICA.md` para:
- Especificación completa de endpoints
- Ejemplos de requests/responses
- Ejemplos de código (cURL, React Native, JavaScript)
- Códigos de error
- Flujos de usuario
- Buenas prácticas de seguridad

---

## 🧪 Pruebas Rápidas

### Con cURL:

```bash
# Iniciar registro
curl -X POST "http://localhost/KRONNO/api/biometric.php?action=register-start" \
  -H "Content-Type: application/json" \
  -d '{"alumno_id": 1, "dispositivo_id": "test-device"}'
```

### Con Postman:

1. Crear request POST a: `http://localhost/KRONNO/api/biometric.php?action=register-start`
2. Body (raw JSON):
```json
{
  "alumno_id": 1,
  "dispositivo_id": "test-device"
}
```
3. Enviar

---

## 📱 Integración con App Móvil

La app móvil (React Native/Flutter) debe:

1. **Capturar huella digital** del sensor biométrico
2. **Convertir a Base64** 
3. **Llamar endpoints de API**:
   - `register-start` → obtener token
   - `register-confirm` → guardar huella
   - `authenticate` → marcar asistencia

Ver `../API_BIOMETRICA.md` para código de ejemplo.

---

## 🔐 Seguridad

- ✅ Tokens con expiración de 5 minutos
- ✅ Bloqueo automático después de 5 intentos fallidos
- ✅ Auditoría completa de intentos
- ✅ Validación de alumno activo
- ✅ IP logging
- ✅ Hashing de huellas (SHA-256)

---

## 📊 Auditoría

Todos los intentos (exitosos/fallidos) se guardan en `biometric_attempts`:

```sql
SELECT * FROM biometric_attempts WHERE alumno_id = 1;
```

---

## 🛠️ Desarrollo

### Modificar un endpoint:

1. Abre `biometric.php`
2. Busca la función del endpoint (ej: `authenticate()`)
3. Modifica la lógica
4. Prueba con cURL o Postman
5. Commit y deploy

### Agregar utilidad:

1. Abre `biometric_utils.php`
2. Agrega método estático a clase `BiometricUtils`
3. Úsalo en `biometric.php`

---

## ❓ Troubleshooting

### Error: "Alumno no encontrado"
- Verifica que el alumno existe en BD
- Confirma que está activo (`estado = 'Activo'`)

### Error: "Token expirado"
- Tokens duran 5 minutos
- Pide nuevo token al usuario

### Error: "La huella no coincide"
- Primer intento: intentar de nuevo
- 5+ intentos: cuenta se bloquea

### Error: "500 Internal Server Error"
- Revisa logs de PHP
- Verifica conexión a BD
- Revisa sintaxis JSON en request

---

## 📈 Próximos Pasos

- [ ] Crear app móvil (React Native/Flutter)
- [ ] Integrar con API biométrica
- [ ] Pruebas en dispositivo real
- [ ] Deploy en Hostinger
- [ ] Capacitar a alumnos
- [ ] Monitorear auditoría

---

## 📞 Contacto & Soporte

Problemas o preguntas:
- Revisa `../API_BIOMETRICA.md`
- Revisa logs en BD: `SELECT * FROM biometric_attempts`
- Prueba con cURL para aislar problema

---

**API Version:** 1.0  
**Última actualización:** 2026-11-08  
**Status:** ✅ Production-ready


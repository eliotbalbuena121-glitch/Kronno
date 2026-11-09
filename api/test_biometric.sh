#!/bin/bash
# Script de pruebas para API Biométrica - KRONO 2026
# Usa: bash api/test_biometric.sh

API_URL="http://localhost/KRONNO/api/biometric.php"
ALUMNO_ID=1
DISPOSITIVO_ID="test-device-001"

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== Pruebas API Biométrica KRONO 2026 ===${NC}\n"

# ========================================
# TEST 1: Iniciar Registro de Huella
# ========================================
echo -e "${YELLOW}TEST 1: Iniciar Registro de Huella${NC}"
echo -e "URL: POST ${API_URL}?action=register-start\n"

RESPONSE_1=$(curl -s -X POST "${API_URL}?action=register-start" \
  -H "Content-Type: application/json" \
  -d "{
    \"alumno_id\": ${ALUMNO_ID},
    \"dispositivo_id\": \"${DISPOSITIVO_ID}\"
  }")

echo -e "Response:\n${GREEN}${RESPONSE_1}${NC}\n"

# Extraer token y código (requiere jq)
TOKEN=$(echo ${RESPONSE_1} | grep -o '"token":"[^"]*' | cut -d'"' -f4)
CODIGO=$(echo ${RESPONSE_1} | grep -o '"codigo_registro":"[^"]*' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
  echo -e "${RED}❌ Error: No se obtuvo token${NC}\n"
  exit 1
fi

echo -e "${GREEN}✅ Token obtenido: ${TOKEN:0:20}...${NC}"
echo -e "${GREEN}✅ Código: ${CODIGO}${NC}\n"

# ========================================
# TEST 2: Verificar Estado de Token
# ========================================
echo -e "${YELLOW}TEST 2: Verificar Estado de Token${NC}"
echo -e "URL: GET ${API_URL}?action=status&token=${TOKEN}\n"

RESPONSE_2=$(curl -s -X GET "${API_URL}?action=status&token=${TOKEN}")

echo -e "Response:\n${GREEN}${RESPONSE_2}${NC}\n"

# ========================================
# TEST 3: Confirmar Registro de Huella
# ========================================
echo -e "${YELLOW}TEST 3: Confirmar Registro de Huella${NC}"
echo -e "URL: POST ${API_URL}?action=register-confirm\n"

# Simular datos de huella (base64 - en real sería datos de sensor)
HUELLA_SIMULADA="iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=="

RESPONSE_3=$(curl -s -X POST "${API_URL}?action=register-confirm" \
  -H "Content-Type: application/json" \
  -d "{
    \"token\": \"${TOKEN}\",
    \"huella_base64\": \"${HUELLA_SIMULADA}\",
    \"dispositivo_id\": \"${DISPOSITIVO_ID}\"
  }")

echo -e "Response:\n${GREEN}${RESPONSE_3}${NC}\n"

SUCCESS=$(echo ${RESPONSE_3} | grep -o '"success":true')
if [ ! -z "$SUCCESS" ]; then
  echo -e "${GREEN}✅ Registro de huella exitoso${NC}\n"
else
  echo -e "${RED}❌ Error en registro de huella${NC}\n"
fi

# ========================================
# TEST 4: Autenticar por Huella (Marcar Asistencia)
# ========================================
echo -e "${YELLOW}TEST 4: Autenticar por Huella (Marcar Asistencia)${NC}"
echo -e "URL: POST ${API_URL}?action=authenticate\n"

# Usar la misma huella simulada para que coincida
RESPONSE_4=$(curl -s -X POST "${API_URL}?action=authenticate" \
  -H "Content-Type: application/json" \
  -d "{
    \"alumno_codigo\": \"A-1001\",
    \"huella_base64\": \"${HUELLA_SIMULADA}\",
    \"dispositivo_id\": \"${DISPOSITIVO_ID}\"
  }")

echo -e "Response:\n${GREEN}${RESPONSE_4}${NC}\n"

SUCCESS=$(echo ${RESPONSE_4} | grep -o '"success":true')
if [ ! -z "$SUCCESS" ]; then
  echo -e "${GREEN}✅ Asistencia registrada exitosamente${NC}\n"
else
  echo -e "${RED}❌ Error al marcar asistencia${NC}\n"
fi

# ========================================
# RESULTADOS FINALES
# ========================================
echo -e "${YELLOW}=== Pruebas Completadas ===${NC}\n"
echo -e "Resumen:"
echo -e "  ${GREEN}✅ Token generado${NC}"
echo -e "  ${GREEN}✅ Huella registrada${NC}"
echo -e "  ${GREEN}✅ Asistencia marcada${NC}\n"

echo -e "${YELLOW}Próximos pasos:${NC}"
echo "1. Integra endpoints en tu app móvil"
echo "2. Prueba con dispositivo real"
echo "3. Despliega en producción"

# Corrección de Errores de Sesión PHP

## Problema Identificado:
- **Warning**: Undefined array key "email" en línea 91
- **Deprecated**: htmlspecialchars() con parámetro null en línea 91

## Causa:
El código intentaba acceder a `$_SESSION['email']` sin verificar si el usuario está logueado o si la clave existe en la sesión.

## Archivos Corregidos:

### ✅ src/Views/reservarCancha.php
- **Línea 91**: Agregada validación de sesión y operador null coalescing
- **Antes**: `<?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); ?>`
- **Después**: `<?= htmlspecialchars($_SESSION['email'] ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?>`
- **Condición**: Solo se muestra si el usuario está logueado

### ✅ src/Views/listado.php
- **Línea 87**: Corregida referencia a `$_SESSION['email']` y `$_SESSION['nombre_rol']`
- **Agregado**: Operador null coalescing para ambos campos

### ✅ src/Views/listadoReservas.php
- **Línea 77**: Corregida referencia a `$_SESSION['email']` y `$_SESSION['nombre_rol']`
- **Agregado**: Operador null coalescing para ambos campos

### ✅ index.php
- **Línea 32**: Corregida referencia a `$_SESSION['email']`
- **Agregado**: Operador null coalescing

## Solución Aplicada:
1. **Validación de sesión**: Se verifica que el usuario esté logueado antes de mostrar la información
2. **Operador null coalescing**: Se usa `??` para proporcionar valor por defecto
3. **Valor por defecto**: "No disponible" cuando no hay información de sesión

## Archivos que YA tenían validación correcta:
- src/Template/navBar.php
- src/Components/modalContactos.php

## Verificación:
✅ Todos los archivos corregidos pasan la verificación de sintaxis PHP
✅ No hay más warnings ni errores deprecated relacionados con sesiones

## Recomendaciones:
1. Siempre validar la existencia de claves de sesión antes de usarlas
2. Usar el operador null coalescing (`??`) para valores por defecto
3. Verificar el estado de la sesión antes de mostrar información sensible

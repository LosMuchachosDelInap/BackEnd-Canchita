# BackEnd-Canchita 🏈

Sistema de gestión de reservas de canchas deportivas - Backend API en PHP

## 📋 Descripción

Este es el backend del sistema "La Canchita de los Pibes", desarrollado en PHP con arquitectura orientada a objetos. Proporciona una API REST para gestionar reservas de canchas deportivas.

## 🛠️ Tecnologías Utilizadas

- **PHP 8.0+**
- **MySQL/MariaDB**
- **Composer** - Gestión de dependencias
- **PHPMailer** - Envío de correos electrónicos
- **Bootstrap 5** - Estilos (para vistas de prueba)

## 📁 Estructura del Proyecto

```
BackEnd-Canchita/
├── src/
│   ├── Components/          # Componentes reutilizables
│   ├── Controllers/         # Controladores de la aplicación
│   ├── Model/              # Modelos de datos
│   ├── ConectionBD/        # Configuración de base de datos
│   ├── Template/           # Templates (para vistas de prueba)
│   └── Views/              # Vistas (para testing/admin)
├── vendor/                 # Dependencias de Composer
├── composer.json          # Configuración de Composer
└── README.md              # Este archivo
```

## 🚀 Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/LosMuchachosDelInap/BackEnd-Canchita.git
   cd BackEnd-Canchita
   ```

2. **Instalar dependencias:**
   ```bash
   composer install
   ```

3. **Configurar base de datos:**
   - Importar `src/ConectionBD/lacanchitadelospibes.sql`
   - Configurar conexión en `src/ConectionBD/CConection.php`

4. **Configurar variables de entorno:**
   - Copiar `servidor.env.example` a `servidor.env`
   - Configurar credenciales de email y base de datos

## 🔧 Configuración

### Base de Datos
Editar `src/ConectionBD/CConection.php`:
```php
private $host = 'localhost';
private $username = 'tu_usuario';
private $password = 'tu_contraseña';
private $database = 'lacanchitadelospibes';
```

### Email
Configurar en `src/Controllers/mail_config.php`:
```php
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'tu_email@gmail.com';
$mail->Password = 'tu_contraseña_app';
```

## 📚 API Endpoints

### Autenticación
- `POST /src/Controllers/validarUsuario.php` - Iniciar sesión
- `POST /src/Controllers/registrarUsuario.php` - Registrar usuario
- `GET /src/Controllers/cerrarSesion.php` - Cerrar sesión

### Reservas
- `POST /src/Controllers/reservarCanchaController.php` - Crear reserva
- `GET /src/Controllers/listadoReservasController.php` - Listar reservas

### Empleados (Admin)
- `GET /src/Controllers/listadoEmpleadosController.php` - Listar empleados
- `POST /src/Controllers/modificarEmpleado.php` - Modificar empleado
- `DELETE /src/Controllers/eliminarEmpleado.php` - Eliminar empleado

### Contacto
- `POST /src/Controllers/contactoController.php` - Enviar mensaje de contacto

## 🧪 Testing

### Verificar sintaxis:
```bash
php check_syntax.php
```

### Verificar base de datos:
```bash
php verificar_bd.php
```

### Probar envío de emails:
```bash
php test_mail.php
```

## 📋 Funcionalidades

- ✅ Gestión de usuarios y autenticación
- ✅ Sistema de reservas con calendario
- ✅ Gestión de empleados (Admin)
- ✅ Envío de emails de confirmación
- ✅ Panel de administración
- ✅ Responsive design
- ✅ Validación de datos
- ✅ Manejo de errores

## 🔄 Próximos Pasos

Esta aplicación está siendo refactorizada para funcionar como API REST pura para el frontend Angular:

1. **Conversión a API REST** - Eliminar vistas HTML
2. **Implementación JWT** - Autenticación por tokens
3. **Documentación OpenAPI** - Swagger/OpenAPI specs
4. **CORS Configuration** - Para Angular frontend

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Añadir nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## 👥 Autores

- **Los Muchachos del INAP** - *Desarrollo inicial* - [LosMuchachosDelInap](https://github.com/LosMuchachosDelInap)

## 📞 Contacto

Para soporte técnico o consultas:
- Email: info@canchitapibes.com
- Teléfono: +54 11 1234-5678

---

⭐ Si te gusta este proyecto, ¡dale una estrella en GitHub!
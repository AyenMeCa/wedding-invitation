# Stiven & Mary — Invitación de boda

Invitación digital de boda + sistema de gestión de invitados.

## Estructura

- `public/` — Invitación (HTML de una sola página, CSS/JS inline) y fotos.
- `api/` — Backend PHP + MySQL para RSVP y gestión de invitados.
- `admin/` — Panel de administración (sin autenticación, se protege ocultando la URL).

## Requisitos

- PHP 8+, MySQL, Apache (probado con Laragon en Windows).
- Configurar `api/config.php` con las credenciales de tu base de datos.
- Crear la tabla `invitados` (id, nombre, cupos, estado, created_at, responded_at).

## Fecha

02 de noviembre de 2026.

# Guía de Contribución

¡Gracias por tu interés en contribuir al Sistema POS Pipos!

## Flujo de Trabajo

1. Crea un **Fork** del repositorio.
2. Crea una rama para tu funcionalidad (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza tus cambios siguiendo los estándares del proyecto.
4. Envía un **Pull Request** a la rama `main`.

## Estándares de Código

Este proyecto utiliza **Laravel Pint** para asegurar la calidad del código. Antes de enviar tus cambios, ejecuta:

```bash
./vendor/bin/pint
```

## Testing

Es obligatorio que todas las nuevas funcionalidades incluyan pruebas. Asegúrate de que todos los tests pasen antes de solicitar una revisión:

```bash
php artisan test
```

## Pull Requests

- Usa la plantilla provista en el PR.
- Adjunta evidencias visuales (capturas) si modificas la interfaz.
- Asegúrate de obtener al menos 1 aprobación (Code Review).

---

© 2025 Equipo de Desarrollo - Comisión 2.2
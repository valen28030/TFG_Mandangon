# TFG_Mandangon

**Mandangon** es una aplicación móvil de gastronomía desarrollada como Trabajo de Fin de Grado. Su objetivo es proporcionar una experiencia completa para los amantes de la cocina, permitiendo registrar recetas, gestionar listas de la compra y explorar reseñas de establecimientos hosteleros.

---

## 📱 Descripción de la App

Mandangon es una app centrada en la experiencia culinaria del usuario. Desde una interfaz intuitiva y visualmente amigable, permite:

- Registrar y consultar recetas personales o prediseñadas.
- Añadir ingredientes con cantidades y tiempos detallados.
- Gestionar una lista de la compra personalizable.
- Explorar restaurantes y bares por categoría o tipo de cocina.
- Valorar y dejar reseñas en establecimientos.
- Acceder a un perfil personalizado con funciones adicionales.

---

## 👨‍🍳 Funcionalidades principales

### Recetas
- Visualización de un listado de recetas preexistentes.
- Creación de nuevas recetas con campos como: nombre, ingredientes, instrucciones y tiempos.
- Gestión de listas de recetas favoritas.

### Hostelería
- Exploración de restaurantes, bares y cafeterías filtrados por estilo.
- Posibilidad de localización y creación de reseñas por parte del usuario.
- Sistema de búsqueda avanzada y favoritos.

### Reseñas
- Clasificación de establecimientos mediante iconos personalizados.
- Moderación de lenguaje y valoración del 1 al 5.
- Visualización y escritura de comentarios.

### Lista de la compra
- Creación y almacenamiento de múltiples versiones.
- Organización por eventos como “barbacoa”, “compra mensual”, etc.

## 🧩 Dependencias

Asegúrate de tener instaladas las siguientes dependencias en tu `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  firebase_core: ^2.0.0
  cloud_firestore: ^4.0.0
  firebase_auth: ^4.0.0
  provider: ^6.0.0
  shared_preferences: ^2.0.15
  # y otras necesarias para UI, navegación, formularios...

---

## 🧱 Estructura del proyecto

El código fuente está organizado por pantallas y funcionalidades:


# 🥘 TFG_Mandangon

**Mandangon** es una aplicación de gastronomía desarrollada como Trabajo de Fin de Grado. Su propósito es ofrecer a los usuarios una herramienta intuitiva para crear, consultar y compartir recetas, gestionar listas de la compra, descubrir establecimientos hosteleros y publicar reseñas culinarias.

---

## 📱 Descripción de la App 📌

Mandangon es una app centrada en la experiencia culinaria del usuario. Desde una interfaz intuitiva y visualmente amigable, permite:

- Crear perfiles mediante autenticación segura.
- Consultar y registrar recetas propias o favoritas.
- Gestionar listas de la compra, clasificadas por ocasión.
- Explorar restaurantes, bares y cafeterías por estilo.
- Publicar y leer reseñas sobre recetas y locales.

## 🧭 Estructura del proyecto

```yaml
lib/
├── pantallas/ # UI principal: login, registro, inicio, etc.
├── funciones/lc/ # Funciones relacionadas con la lista de la compra
├── funciones/usuarios/ # Funciones de autenticación y gestión de usuarios
├── funciones/restaurantes/ # Lógica de exploración y gestión de restaurantes
├── funciones/resenias/ # Módulo de reseñas
├── firebase_options.dart # Configuración de Firebase
```


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


# 7.6 Tutoría Programación

## HITO 1: Arquitectura inicial y base funcional del sistema

### Requerimientos:

-   **Definición de la arquitectura del sistema:**
    -  Si es manual: Implementar arquitectura en 3 capas (MVC) propia y documentarla.
    -  Si es con framework: Configurar el proyecto base y documentar la estructura de directorios y componentes principales.
    -  Diseño y validación del modelo de datos (DER en conjunto con Tutoría de Bases de Datos).
-  **Configuración del entorno de trabajo:**
    -  Repositorio en GitHub organizado en ramas.
    -  Configuración de contenedores básico (aplicación + base de datos).
-  **Entrega de módulos iniciales:**
    -  Sistema de autenticación de usuarios con control de sesiones.
    -  Primer CRUD completo (mínimo 1 entidad).
-  **Documentación inicial:**
    -  Readme del proyecto, guía de despliegue local y diagramas de arquitectura.

### Entregable:

Proyecto funcional en entorno local con autenticación de usuarios y un módulo completo CRUD.

## HITO 2: Funcionalidades avanzadas, seguridad y escalabilidad

### Requerimientos:

-  **Persistencia avanzada:**
    -  Uso de procedimientos almacenados en al menos dos procesos clave.
    -  Ampliar el CRUD a más entidades.
-  **Seguridad:**
    -  Autenticación segura.
    -  Validación y sanitización de datos, evitando inyecciones SQL.
    -  Si usa un framework, ajustarse a los estándar de seguridad del framework elegido.
-  **Patrones de diseño:**
    -  Implementar al menos tres patrones de diseño (Factory, Singleton, Facade, Strategy, etc.).
    -  Uso de logs en archivos o base de datos (en conjunto con el docente de Tutoría de Base de Datos).
    -  Integración de librerías externas:
        -  Configuración de Composer para instalar librerías.
-  **Arquitectura:**
    -  Configuración de rutas amigables (.htaccess o equivalentes del framework).
    -  Organización modular y aplicación de principios SOLID.

### Entregable:

Proyecto estable con mínimo tres módulos completos, sistema seguro de autenticación y ejemplos claros de patrones de diseño aplicados.

## HITO 3: Integración de APIs, optimización y despliegue final

### Requerimientos:

-  **POO avanzada:**
    -  Uso de herencia, interfaces, traits y excepciones personalizadas en la lógica de negocio.
-  **Integración de servicios externos:**
    -  Consumo de al menos una API externa, procesando datos JSON (mostrar en la interfaz o persistir en base de datos).
-  **Optimización y mejoras:**
    -  Refactorización aplicando principios SOLID y Clean Code.
    -  Mejoras en el front-end.
-  **Despliegue:**
    -  Configuración con imágenes personalizadas con contenedores y variables de entorno.
    -  Subir imágenes a un gestor de servicios de repositorio oficial, documentar el despliegue en un servidor.
-  **Documentación y mini defensa de la tutoría:**
    -  Documentación técnica completa (diagramas UML, guía de instalación, composer.json, etc.).
    -  Presentación final explicando arquitectura, patrones y decisiones técnicas.

### Entregable:

Sistema completo en producción con API integrada, documentación lista y versión final estable.

---

*Documento de requerimientos y elaboración de proyectos de pasaje de grado, elaborado por los docentes Luis E. Fagúndez, Gabriel Bolsi, Carin Molina, Mario Malagrino, Juan D. Pajares.*
*INSTITUTO SUPERIOR BRAZO ORIENTAL*
*Introducción a los Proyectos de Egreso de la Tecnicatura en Redes y Software – DGETP-UTU*
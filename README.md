# Manager JsonPlaceHolder

¡Hola! Este `README.md` te guiará a través de la configuración y ejecución de este proyecto.

---

## Configuración del Proyecto

Para empezar a usar este proyecto, sigue estos sencillos pasos.

### Requisitos Previos

Asegúrate de tener instaladas las siguientes versiones:

* **PHP:** Versión 8.2 o superior.
* **Node.js:** Versión 20.18.3 o superior.

### Instalación

1.  **Descarga el proyecto:** Obtén el código fuente de este repositorio.
2.  **Instala dependencias de PHP:** Abre tu terminal en la raíz del proyecto y ejecuta:
    ```bash
    composer install
    ```
3.  **Instala dependencias de Node.js:** En la misma terminal, ejecuta:
    ```bash
    npm install
    ```

---

### Configuración del Entorno

Una vez instaladas las dependencias, necesitarás configurar algunas variables de entorno en tu archivo `.env`:

1.  **Variables de Caché y Cola:** Agrega las siguientes líneas a tu archivo `.env`:
    ```
    QUEUE_CONNECTION=database
    CACHE_STORE=database
    CACHE_DRIVER=database
    ```
2.  **API de JSONPlaceholder:** Para integrar la API, añade esta variable con la URL correspondiente:
    ```
    API_JSON_PLACEHOLDER="Url de la api de JsonPlaceHolder"
    ```
    (Asegúrate de reemplazar `"Url de la api de JsonPlaceHolder"` con la URL real de la API de JSONPlaceholder).

---

### Ejecución y Puesta en Marcha

Con la configuración de entorno lista, puedes proceder a ejecutar el proyecto:

1.  **Ejecuta las migraciones de la base de datos:**
    ```bash
    php artisan migrate
    ```
2.  **Inicia las colas y los cron jobs:** Abre dos terminales separadas y en cada una ejecuta uno de los siguientes comandos:
    * Para las colas:
        ```bash
        php artisan queue:work
        ```
    * Para los cron jobs:
        ```bash
        php artisan schedule:work
        ```
3.  **Crea el enlace simbólico del almacenamiento:**
    ```bash
    php artisan storage:link
    ```
4.  **Inicia el servidor de desarrollo:**
    ```bash
    composer run dev
    ```

---

### Acceso a la Aplicación

¡Listo! Ahora puedes acceder a la aplicación en tu navegador web:

**http://localhost:8000**
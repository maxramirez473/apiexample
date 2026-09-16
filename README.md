## Proyecto de ejemplo API - REST
### Este proyecto implementa un ejemplo de API - REST como ejemplo del desarrollo y despliegue. 
*   Para ejecutar el proyecto se debe clonar el repositorio.
*   Ejecutar <i>Composer install</i> dentro en la raiz del proyecto.
*   Copiar el archivo de variables de entorno <i>cp .env-example .env</i>.
*   Ejecutar <i>php artisan key:generate</i> para generar la clave de la aplicación.
*   Cargar los valores para las demás variables de entorno.
*   Ejecutar <i>php artisan serv</i> para que la aplicación comience.

## Apertura de swagger
*   Para acceder al swagger se debe dirigir a la ruta configurada en APP_URL + api/documentation
*   Para autenticarse debe registrarse con el endpoint <i>/register</i> para generarse un usuario y obtener el token.
*   Para autorizarse debe agregar en el boton de autorize usando <i>"Bearer 1|abc123..."</i>


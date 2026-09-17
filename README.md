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

## Despliegue en Railway (Nixpacks/Railpack + MySQL)

Railway construye la imagen con Railpack (usa FrankenPHP como runtime PHP) y separa el ciclo de deploy en tres etapas configurables desde **Settings → Deploy** del servicio: *Build Command*, *Pre-Deploy Command* y *Start Command*. Es importante no mezclar migraciones ni tareas que requieran conexión a la base de datos dentro del Build Command, ya que el contenedor de build **no tiene acceso** a la red privada de Railway (por ejemplo `mysql.railway.internal` no resuelve durante el build).

### Build Command
Solo debe instalar dependencias, compilar assets y generar cachés/documentación que no dependan de la base de datos:
```
composer install && npm install --production && php artisan l5-swagger:generate && php artisan config:cache && php artisan route:cache
```

### Pre-Deploy Command
Aquí sí hay acceso a la red privada (host `mysql.railway.internal`), por lo que las migraciones deben ejecutarse en este paso y no en el build:
```
php artisan migrate --force
```
Railway reintenta este comando automáticamente si la base de datos aún no está lista al arrancar el contenedor (normal ver algunos `Connection refused` transitorios en el log antes de que la migración corra con éxito).

### Start Command
```
php artisan serve --host=0.0.0.0 --port=$PORT
```
* Debe escuchar en `0.0.0.0` y en el puerto de la variable `$PORT` que inyecta Railway (nunca un puerto fijo).
* No uses `&&` para encadenar `migrate` aquí: si la migración falla, el servidor nunca arranca y la app queda inalcanzable (502 "Application failed to respond"). Las migraciones van en el Pre-Deploy Command.

### Variables de entorno / networking a revisar
* En **Settings → Networking** del dominio público, el **Target Port** debe coincidir con el puerto donde escucha la app (el mismo valor de `$PORT`, normalmente `8080`). Si no coincide, Railway devuelve 502 con "connection refused" aunque la app esté corriendo bien.
* `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` y `DB_DATABASE` deben tomarse de las variables del servicio de MySQL de Railway (ideal referenciarlas con `${{MySQL.VARIABLE}}` en vez de copiarlas a mano, para que se actualicen solas si recreás la base).
* `APP_ENV=production` en Railway (no dejar `local`).
* `L5_SWAGGER_GENERATE_ALWAYS=false` en producción, ya que la documentación se genera una vez en el Build Command; dejarlo en `true` regenera el spec en cada visita a `/api/documentation` y puede causar timeouts (502/499).
* Railway termina el TLS en su proxy y reenvía HTTP al contenedor, por lo que Laravel puede generar URLs de assets con `http://` en vez de `https://` (error de "mixed content" en el navegador al cargar `/api/documentation`). Esto está resuelto forzando el esquema HTTPS en producción desde `AppServiceProvider::boot()`.
* Nunca subas el archivo `.env` con credenciales reales a un repositorio público; usa las variables de entorno del servicio en Railway para los secretos.


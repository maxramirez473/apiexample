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

## Depuración con Xdebug (entorno local con XAMPP en Windows)

Esta sección aplica solo al entorno de desarrollo local. **No se instala ni se configura Xdebug en Railway/producción.**

### 1. Instalar la extensión
*   Abrir `http://localhost/dashboard/phpinfo.php` (o un archivo con `<?php phpinfo();`), copiar todo el contenido y pegarlo en el asistente de https://xdebug.org/wizard para saber qué DLL descargar.
*   Verificar tres datos: versión de PHP (8.2 o superior para Laravel 12), arquitectura (x64) y Thread Safety (en XAMPP suele ser `TS`).
*   Renombrar la DLL descargada a `php_xdebug.dll` y copiarla en `C:\xampp\php\ext\`.

### 2. Configurar el php.ini
Editar `C:\xampp\php\php.ini` (o desde el panel de XAMPP: Apache → Config → PHP (php.ini)) y agregar al final. Si ya existe alguna línea de Xdebug en el archivo, comentarla o borrarla para no duplicar:
```ini
[Xdebug]
zend_extension=xdebug
xdebug.mode=debug
xdebug.start_with_request=trigger
xdebug.client_host=127.0.0.1
xdebug.client_port=9003
xdebug.idekey=VSCODE
; Opcional: log para diagnosticar problemas (la carpeta debe existir)
; xdebug.log=C:\xampp\tmp\xdebug.log
```
*   `trigger`: Xdebug solo se activa cuando la request trae la cookie `XDEBUG_SESSION` (o el parámetro `XDEBUG_SESSION_START`). Si se prefiere que se active en todas las requests, usar `xdebug.start_with_request=yes` (en ese caso el listener del IDE debe estar siempre activo, o la API se vuelve lenta).
*   Reiniciar Apache y verificar con `C:\xampp\php\php.exe -v`: debe indicar `with Xdebug v3.x.x`.
*   Si se usa `php artisan serve`, comprobar con `php --ini` que se cargue el `php.ini` de XAMPP y no el de otro PHP del PATH.

### 3. Variable de entorno para Swagger (L5-Swagger)
Swagger UI hace las requests desde el navegador, por lo que la cookie de Xdebug solo viaja si la página de Swagger y la API comparten **exactamente el mismo host**. Si la API corre en `127.0.0.1:8000` pero Swagger se abre desde `localhost:8000` (o al revés), el navegador los trata como orígenes distintos y la cookie no se envía. Para evitarlo, agregar en el `.env`:
```dotenv
L5_SWAGGER_CONST_HOST=http://localhost:8000/api/documentation
```
Luego limpiar la caché y regenerar la documentación:
```
php artisan config:clear
php artisan l5-swagger:generate
```
Abrir siempre Swagger desde `http://localhost:8000/api/documentation` (no desde `127.0.0.1`).

### 4. Configurar el IDE (VS Code)
Instalar la extensión **PHP Debug** (`xdebug.php-debug`) y crear el archivo `.vscode/launch.json`:
```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003
    }
  ]
}
```
No hace falta `pathMappings` porque el proyecto corre localmente (solo sería necesario con Docker/Sail).

En PhpStorm: activar *Start Listening for PHP Debug Connections* y confirmar el puerto 9003 en *Settings → PHP → Debug*.

### 5. Depurar un endpoint
1.  Poner un breakpoint en el método del controller que se quiere depurar.
2.  Iniciar el listener en el IDE (F5 en VS Code).
3.  Con `start_with_request=trigger`, setear la cookie desde la consola del navegador (F12) estando en la página de Swagger, o usar la extensión del navegador *Xdebug helper* con IDE key `VSCODE`:
    ```js
    document.cookie = "XDEBUG_SESSION=VSCODE; path=/";
    ```
4.  Ejecutar el endpoint con **Try it out**: el IDE debería frenar en el breakpoint.

Para probar desde `curl` o Postman:
```
curl -H "Cookie: XDEBUG_SESSION=VSCODE" http://localhost:8000/api/tu-endpoint
```

### Si no frena
*   Verificar que Xdebug esté cargado (`php -v`) y que el listener esté activo en el puerto 9003.
*   Revisar en la pestaña Network del navegador que la request lleve la cookie `XDEBUG_SESSION`.
*   Revisar `xdebug.log`: si dice "Could not connect to debugging client", el IDE no está escuchando o el puerto no coincide; si no registra nada, Xdebug no se activó en esa request (falta el trigger).
*   Confirmar que el firewall de Windows no bloquee el puerto 9003 y que no haya dos `php.ini` distintos.

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


## Tests y despliegue continuo (GitHub Actions + Railway)

El flujo es: **push a `main` → GitHub Actions corre los tests → Railway despliega solo si los tests pasan**.

### Tests
Los tests están en `tests/` y corren contra SQLite en memoria (no necesitan MySQL):
*   `tests/Feature/AuthTest.php`: registro, login, logout y rutas protegidas con token Sanctum.
*   `tests/Feature/AlumnoTest.php`: autenticación y validaciones del endpoint `/api/alumnos`.
*   `tests/Unit/UserTest.php`: test unitario del modelo `User` (sin HTTP ni base de datos).

Para correrlos localmente:
```
php artisan test
```
Los tests Feature usan `RefreshDatabase` para recrear las tablas en cada test, y `User::factory()` para crear usuarios de prueba.

### Workflow de GitHub Actions
El archivo `.github/workflows/tests.yml` instala PHP 8.2 y las dependencias, genera una `APP_KEY` y ejecuta `php artisan test`. Debe correr en `push` sobre la rama que despliega Railway (`main`).

### Configuración en Railway
1.  En el servicio, ir a **Settings** y activar **Wait for CI** (solo aparece si el repositorio tiene un workflow con trigger `push`).
2.  Con esa opción activa, el deploy queda en estado `WAITING` mientras corren los tests. Si fallan, el deploy pasa a `SKIPPED` y la versión anterior sigue en producción. Si pasan, se despliega normalmente.
3.  Antes de la demo, revisar en GitHub que el commit no tenga checks fallidos de otras apps instaladas: Railway considera todos los check suites, no solo los de este workflow.
4.  Si un deploy no se dispara después de un CI exitoso, se puede forzar con **Deploy Latest Commit** (Cmd/Ctrl + K).

### Guion de la demo
1.  **Estado inicial**: push a `main` con los tests. Actions queda en verde y Railway despliega.
2.  **Cambio erróneo**: en `AuthController::login`, cambiar el status de credenciales inválidas de `401` a `200`. Commit y push.
    *   El test `test_el_login_con_credenciales_invalidas_devuelve_401` falla, Actions queda en rojo y el deploy en Railway queda `SKIPPED`. La API en producción sigue con la versión anterior.
3.  **Corrección**: volver el status a `401`. Commit y push.
    *   Los tests pasan y Railway despliega la nueva versión.
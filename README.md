# TestPeruApps

Proyecto creado para cumplir con la prueba técnica para postular como desarrollador BACKEND

-   El código del proyecto se encuentra de manera pública usando **git**
-   Se utilizó PHP + LARAVEL + MySQL
-   Se creó una colección en Postman para ejecutar los servicios (https://documenter.getpostman.com/view/8050459/SVSNK7vY?version=latest)

# Instalación

-   Clonar el proyecto desde git https://github.com/quickdunk/testPeruApps
-   Instalar composer
-   Instalar laravel https://laravel.com/docs/5.8/installation
-   Asegurarse que en la variable PATH de entorno se encuentre la carperta para ejeciutar el comando **_laravel_** (En el link anterior se puede ver los rutas para configurar este comando)
-   Dentro de la carpeta del proyecto:

```sh
# Crear base de datos
$ php artisan migrate

# Poblar tabla
$ php artisan db:seed --class=UserTableSeeder

# En caso sucede algun problema, borramos la base de datos y podemos repetir los pasos anteriores
$ php artisan migrate:reset
$ php artisan migrate
$ php artisan db:seed --class=UserTableSeeder

# Publicamos la carpeta publica storage para acceder a las imágenes de los usuarios
$ php artisan storage:link

# Finalmente iniciamos el servidor
$ php artisan serve
```

-   Luego de la población la tabla contendrá 50 usuarios con el password **adminadmin**. Se puede iniciar sesión con cualquiera de ellos

La aplicación tiene disponible las siguientes rutas:

| Nombre             | Método | URL                                               |
| ------------------ | ------ | ------------------------------------------------- |
| Iniciar sesión     | POST   | http://127.0.0.1:8000/api/user/login              |
| Lista paginada     | GET    | http://127.0.0.1:8000/api/user                    |
| Usuario por ID     | GET    | http://127.0.0.1:8000/api/user/:id                |
| Crear usuario      | POST   | http://127.0.0.1:8000/api/user                    |
| Actualizar usuario | PUT    | http://127.0.0.1:8000/api/user/:id                |
| Subir foto usuario | POST   | http://127.0.0.1:8000/api/user/profile/update/:id |
| Borrar usuario     | DELETE | http://127.0.0.1:8000/api/user/:id                |
| Cerrar sesión      | POST   | http://127.0.0.1:8000/api/user/logout             |

# Tecnología y características

-   Para la seguridad se utilizó JSON Web Tokens (https://github.com/tymondesigns/jwt-auth)
-   Para la paginación se usó características de Laravel (https://laravel.com/docs/5.8/pagination#paginating-eloquent-results)
-   Para la población de datos se usó Faker (https://github.com/fzaninotto/Faker)
-   Las pruebas se ejecutaron con Postman y se publicó la colección (https://www.getpostman.com/collections/0309209381f970711643)
-   Las imágenes se almacenan en _**/storage/app/public/uploads/images**_
-   Para los filtros de la lista de usuario se pueden usar como parametros el nombre de las siguientes columnas: _user_name_, _first_name_, _last_name_, _email_. Estas pueden ser agregadas en la URL del servicio **Lista paginada**.
-   La paginación puede establecerse usando el parámetro **_page_**
-   El ordenamiento se hace por el valor de la columna **_last_name_** y los posibles valores son **_asc_** y **_desc_**
-   Los paraemtros de filtros de columnas, el ordenamiento y la paginación pueden combinarse

| Parametro  | Referencia                                             | Ejemplo URL                                 |
| ---------- | ------------------------------------------------------ | ------------------------------------------- |
| page       | Es el número de página                                 | http://127.0.0.1:8000/api/user?page=1       |
| sort       | Es el orden de los resultados ascendente o descendente | http://127.0.0.1:8000/api/user?sort=desc    |
| email      | Es filtro de email                                     | http://127.0.0.1:8000/api/user?email=e      |
| user_name  | Es filtro de nombre de usuario                         | http://127.0.0.1:8000/api/user?user_name=e  |
| first_name | Es filtro de nombre real del usuario                   | http://127.0.0.1:8000/api/user?first_name=e |
| last_name  | Es filtro de apellido del usuario                      | http://127.0.0.1:8000/api/user?last_name=e  |

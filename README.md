# WellnessTrack - api

Este repositorio cuenta con el proyecto creado con Symfony usando Doctrine, el cual cuenta es usado en el proyecto completo WellnessTrack como api.

## Instalación

Para el correcto funcionamiento del proyecto es necesario:

- Symfony, usado v5.8.19
- Doctrine
- Php, usado v8.2.10
- Composer, v2.7.1

Ya que aquí se encuentra la conexión con la base de datos:

- Xampp, usado v8.2.12
- Docker desktop, usado v 4.29

## Ejecución

Para la Ejecución del servidor se han de realizar los siguientes comandos.

Lo primero que debemos hacer será prepara el entorno de docker que necesitaremos.
Con el siguiente comando crearemos la red para el contenedor.

```bash
    docker network create --driver bridge --subnet 172.79.0.0/16 --gateway 172.79.0.1 redDocker
```

Lo siguiente será lanzar el docker que usaremos para tener la base de datos, en el siguiente comando uso una imagen personalizada creada por mi para este proyecto que ya cuenta con xampp instalado.

```bash
    docker run -it --name backdocker -p 8080:80 -p 8081:3306 -p 8000:8000 --ip 172.79.0.3 --network red1 -v ../WellnessTrack-api:/home/docker hehedaniel/backcompleto:backterminado
```

Hecho estos comandos deberemos haber entrado al contenedor, una vez dentro deberemos ejecutar:

```bash
    /opt/lampp/lampp start
```

Y tras esto tendremos listo la base de datos, lo podremos comprobar entrado a:

- [http:/localhost:8080](http:/localhost:8080)

Hecho esto podemos empezar con el servidor Symfony.

Lo primero será, ya que bajamos el códgigo desde github, instalar las dependencias.

```bash
    composer update
```

Si hemos recibido una salida correcta sin nigun tipo de error lo siguiente será lanzar el servidor

```bash
    symfony server:start
```

Hecho esto tendremos el servidor lanzado y lusto para usar en la siguiente dirección:

- [http:/localhost:8000](http:/localhost:8000)

Para saber que sobre que endpoints puedes probar el servidor puedes leer la documentación generada con [PhpDocumentor](https://www.phpdoc.org) que se encuentra en los archivos del servidor

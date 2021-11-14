# Symfony URL Shorter

Esto es un proyecto para generar un pequeño site que redirecciona webs con urls más cortas

## Instalación y puesta en marcha

Requisitos:

- PHP (Probado en versión 8.0.12)
- Composer (Probado en versión 2.1.12)
- Docker/Podman (Probado en Podman 3.4.5)
- Si se utiliza Podman, en vez de docker-compose se puede usar la herramienta podman-compose

Instalar las dependencias con `composer install`

Se adjunta un fichero de configuración de Docker compose para dar de alta una base de datos Postgres compatible. La configuración actual esta pensada para tener el volumen de datos persistentes dentro del mismo directorio del proyecto, en `/volumes/db-data` (Crear directorio si no existe)

Realizar la migración de la DB con `bin/console doctrine:migrations:migrate`

Una vez puesto en marcha, ejecutar la app usando `symfony server:start`

## Instrucciones de uso

Asumimos que el servicio se esta ejecutando en localhost:8000

Primero hay que dar de alta un usuario que servirá de administrador en la URL

http://localhost:8000/register

A partir de este punto para poder administrar los sitios web, hay que acceder a través de

http://localhost:8000/user/panel

Donde será necesario iniciar sesión con un usuario existente.

Desde esta vista se pueden añadir distintas URLs y elegir el algoritmo para generar una Url mas corta. A las URLs mas cortas se pueden acceder a traves de

http://localhost:8000/redir/{id}

Tambien se ofrece una vista de estadísticas públicas a través de

http://localhost:8000/stats

Y se pueden acceder a los datos como una API Rest a traves de

http://localhost:8000/api/stats

o

http://localhost:8000/api/stats/{id}

## Añadiendo nuevos algoritmos de recortado de URL

En src/Helpers/UrlShorter:

1. Añadir nueva constante en UrlShortAlgorythm
2. Implementar nuevo algoritmo que implemente la interface `UrlShorter`
3. Añadir nuevo caso en `UrlShorterFactory`

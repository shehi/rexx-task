# Task

## Considerations

* **NO TESTS**: Sorry for that! Due to time constraints, I didn't write any tests. Let's discuss this on a meeting. If time permits, I will try to put some service-level (unit) and infrastructure-level (integration) tests later on. But for now, after 6 hours, I am tired.
* Tried to implement something resembling a real Architecture. Sorry (?!) but being a Software Architect, I am done with spaghetti codes, and similar messes. _This experience I just had, was utterly intolerable!_
* Implementation doesn't feature IoC container, as such, DI is non-existent. Services are init'ed in ugly fashion.
* Implementation separates _Domain_, _Application_ and _Infrastructure_ concerns as much as possible, considering all the existing limitations:
  * _Domain_ depends on _Application_ and has no knowledge of its internals.
  * _Application_ depends of _Infrastructure_ and has no knowledge of its internals.
* `./import` command (used via `composer run db:import`, read below) utilizes `DataImportService`.
* Web UI utilizes `DataAggregateService`. Single endpoint via `./index.php`.
* Docker-Compose setup, tested on Linux env. I don't use Mac and Docker in Mac is semi-crippled. If any problems, I can demo it during a meeting.

## Assets

* `data.json` - contains JSON data you provided.
* `ddl.sql` contains DDL script for all necessary tables. Read _Setup_ below for instructions on how to execute it.
* `./.docker` + `docker-compose.yml` - contains Docker Compose assets.

## Setup

* If it is desired for Docker user (inside the `php` container) to have the same UID/GID as the Host-machine user (in order to avoid permission issues):
  ```
  export MYUID=$(id -u);
  export MYGID=$(id -g);
  ```
  **Skip this if Host UID and GID both are `1000`**.
* `docker-compose build`
* `docker-compose up -d` spins the env. `80:80` port attachment on `localhost`. **Make sure Host's port-80 is not occupied!**
* `docker-compose exec php bash -l` to enter the container terminal:
  * `composer run db:init` to import SQL (it resets Db).
  * `composer run db:import` to install dependencies.
  * OR `composer run db` to run both above-mentioned commands in sequence.

## Web UI

* Access the page at http://localhost.

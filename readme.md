# Laravel Drydock

This project is a premade, easy to use local development setup to be used for authoring Laravel applications.

The deliverables of this project structure are:

 - One development container that is also maintained as an [automated build over at Docker Hub](https://hub.docker.com/r/atrauzzi/laravel-drydock)
 - Two docker containers capable of running your project via [nginx](https://www.nginx.com/) and [php-fpm](http://php.net/manual/en/install.fpm.php).

## Installation

### Adding Drydock to Existing Projects

Inside your existing Laravel project, set laravel drydock up as an upstream remote with the following commands:

```
git remote add drydock git@github.com:atrauzzi/laravel-drydock.git
git config remote.drydock.pushurl "Don't push to drydock from projects!"
git fetch drydock
git checkout drydock/master \*
```

### Adding Laravel to Drydock

Alternatively, you can go the other way and add Laravel to a checkout of drydock.

```
git remote add laravel git@github.com:laravel/laravel.git
git config remote.laravel.pushurl "Don't push to laravel from projects!"
git fetch laravel
git checkout laravel/master \*
```

Remember, you can always grab a different version other than the latest by substituting `master` with a valid tag name from [laravel/laravel](https://github.com/laravel/laravel).

## Running

Starting your application locally is as simple as:

```
docker-compose up -d
```

Be aware that depending on the state of your project, laravel and drydock, some environment configuration may have drifted.  That said, I will always try to keep drydock working with the latest version of Laravel.
Feel free to open a ticket with any concerns or issues that might be affecting the default configuration.

If you've just installed Laravel, you will also want to install all your dependencies:

```
./run composer install
./run composer require predis/predis
```

As you can see, the run shell script simply forwards whatever you pass to it into the running container.

## What's in the box?

Most importantly?  Everything is **standard**!  That means you shouldn't need to be aware of platform or hosting specific quirks.  The default docs for the various projects should always be sufficient.

 - PHP 7.1
 - Laravel 5.2.*
 - Postgres 9.5.*
 - Redis 3.0.*

The following ports are exposed to the host:

 - Laravel, 8080
 - Postgres, 5432
 - Redis, 6379
 - Maildev, 8082

This gives you the convenience of being able to run local GUI tools against the environment - I recommend PhpStorm, DataGrip and Redis Desktop.

Port `8082` is running an email trap that will intercept all outbound emails and show them in a convenient interface.

## Meta

After spending time with the various PaaS offerings, it dawned on me that they tend to get in the way.  I've had to support a few teams now attempting to use
services like Google App Engine, Azure Web Sites and Amazon Elastic Beanstalk.  Each platform has a way of saddling devs with cumbersome proprietary environment 
quirks that swallow hours of productivity. Eventually, you end up forced to make compromises and additions to your project that end up feeling a lot like technical debt.

The core issue is that all PaaS offerings are in some way are restricting the runtime, thus breaking PHP and the ecosystem of libraries we enjoy:

 - Inability to write temporary files to the local filesystem (cached templates and optimizations)
 - Non-standard customizations to the PHP runtime - especially when it comes to CURL
 - Limited database, cache, filesystem or other supporting technology choices
 - Confusing pricing structures
 - Not running on the operating system of your choice
 - Brittle support library requirements
 - Obscure DNS and socket limitations
 - Missing PHP extensions
 - Difficult deployment flows
 
The good news today is that most vendors have realized this and have started to offer the ability to run your own custom docker containers on top of their PaaS platforms.

### Local Development - With Infrastructure

With PHP, it's best to run your project in an environment that is similar to what you will be deploying to.  This is what laravel-drydock is all about and it accomplishes this using Docker containers.

Please be sure to have the most current versions of docker (>= 1.10) and docker-compose (>= 1.6).  If you're encountering any issues, this would be a good first thing to check.  If you're not sure of how best to install them, check out [Docker for Windows](https://docs.docker.com/docker-for-windows/) or [Docker for macOS](https://docs.docker.com/docker-for-mac/).

If you need to run any commands like `composer` or `artisan`, simply prefix them with `./run` at the root of this project.  They will be run inside the environment.

Once the environment is running, it will output all server requests, database access, queue access and cache messages.  Repeated queue messages are normal and are just the queue worker polling.

 
We know why they do it - it's to offer scalability!  But when we unpack the excuses, it tends to be that the PaaS vendors go too far.

### Why Laravel?

Due to the high quality and extensive number of abstractions it offers, Laravel is one of the most productive web frameworks out there!
Like all frameworks, with care and understanding, you can use it in a wide variety of configurations and environments.

### Deploying

When you're done authoring your project locally, you're ready to master a snapshot of your project as two docker images.  Use (and adapt) the following commands
as needed:

```
 ./run composer update --no-dev --prefer-dist --optimize-autoloader
 ./run npm install
 ./run jspm install
 ./run gulp
 
docker --log-level=debug build --force-rm --no-cache --pull --file=resources/dockerfiles/php.Dockerfile --tag=atrauzzi/laravel-drydock:php .
docker --log-level=debug build --force-rm --no-cache --pull --file=resources/dockerfiles/nginx.Dockerfile --tag=atrauzzi/laravel-drydock:nginx .
```

This will prepare two images in your local docker image cache that contain your entire project, ready to run!  Keep in mind that most docker registries require that images follow a 
specific naming convention.  Be sure to substitute `atrauzzi/laravel-drydock` above with names that correspond to your own registry.

If you're using a [registry](https://docs.docker.com/registry/) that supports docker's `push` command, distributing your images is easy: 

```
docker push atrauzzi/laravel-drydock:webapp
docker push atrauzzi/laravel-drydock:web
```

Again, please remember to substitute your own registry names above.  If you don't have a registry yet, I highly recommend [Docker Hub](https://hub.docker.com/).

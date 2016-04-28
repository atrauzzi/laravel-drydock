# Laravel Drydock

This project is a premade, easy to use local development setup to be used for authoring Laravel applications.

The deliverables of this project structure are:

 - One development container that is also maintained as an [automated build over at Docker Hub](https://hub.docker.com/r/atrauzzi/laravel-drydock)
 - Two docker containers capable of running your project via [nginx](https://www.nginx.com/) and [php-fpm](http://php.net/manual/en/install.fpm.php).


## Usage

### Local Development - With Infrastructure

At some point, you'll want to run this project in an environment that is similar to what you will be deploying to.  This is what laravel-drydock is all about and we accomplish this using Docker containers.

Please be sure to have the most current versions of docker (>= 1.10) and docker-compose (>= 1.6).  If you're encountering any issues, this would be a good first thing to check.

Before getting started, be sure to check the platform specific notes below.  After that:

 - Ensure that the environment variable `UID` has been exported.  I suggest adding `export UID` in your default profile
 - Copy the example environment file to a new `.env` file, be sure to provide a valid app key.
 - `./run composer install` (you will likely encounter github's rate limit)
 - `./run artisan migrate`
 - `./run npm install`
 - `./run jspm install`
 - `./run gulp`
 - run `docker-compose up`

These are going to download a considerable amount of dependencies for the front and back end, so be prepared to wait a little.

If you need to run any commands like `composer` or `artisan`, simply prefix them with `./run` at the root of this project.  They will be run inside the environment.

Once the environment is running, it will output all server requests, database access, queue access and cache messages.  Repeated queue messages are normal and are just the queue worker polling, the worker may error out a few times until RabbitMQ is fully started.

## What's in the box?

Most importantly?  Everything is **standard**!  That means you shouldn't need to be aware of platform or hosting specific quirks.  The default docs for the various projects should always be sufficient.

 - PHP 7
 - Laravel 5.2.*
 - Postgres 9.5.*
 - Redis 3.0.*
 - RabbitMQ 3.6.*
 - JSPM

I've thrown together some inline controllers in `routes.php` that you can use to see the environment and verify that the queues are working. Be sure to delete them before going live with any containers generated from this project.

The following ports are exposed to the host:

 - Laravel, 8080
 - Postgres, 5432
 - Redis, 6379
 - RabbitMQ Management, 8081
 - Maildev, 8082

This gives you the convenience of being able to run local GUI tools against the environment - I recommend PhpStorm, DataGrip and Redis Desktop.

Port 8082 is running an email trap that will intercept all outbound emails and show them in a convenient interface.  The project's default exception handler has also been customized to render a copy of all exceptions to this email address.  This should be helpful while developing commands and background jobs.


#### Linux
Get the latest version of Docker and Docker Compose installed.  I suggest installing `docker-compose` using pip.

After that, because Docker runs on Linux, consider yourself done!  Everything happens on localhost and is ready for you to use.

#### OSX
A current and working [docker toolbox](https://www.docker.com/products/docker-toolbox) installation should have you covered here.

If you would like to treat your development VM like a local machine, check out these [instructions on setting up NAT forwarding at the command line](https://www.virtualbox.org/manual/ch06.html#natforward).
You can also configure forwarded ports using the VirtualBox GUI using [the instructions found here](http://ask.xmodulo.com/access-nat-guest-from-host-virtualbox.html).

Take a look below at Meta for some information about Docker's latest beta tool for OSX.

#### Windows
Currently, I cannot recommend using Windows for docker-based development.  Until there is an option for running containers in interactive mode, most of drydock's functionality is cut short.
 
Previous versions of this readme do contain some instructions on how to manually set up a virtual machine, although it's quite involved.

Take a look below at Meta for some information about Docker's latest beta tool for Windows.

### Local Development - Standalone

You might find yourself in a situation where you aren't able to or don't need to use the full Dockerized setup.  In these cases, this project is still a pure Laravel project, ready out of the box
to be run without any drivers configured.

If you choose to do this, all state will be kept on the filesystem via sqlite and files in the `laravel/storage/framework` directory.

You'll still have to make sure to install [composer](http://getcomposer.org) and PHP on your machine.  Then it's just a matter of going into the `laravel` directory and typing:

```
./artisan serve
```

If you wish to use the default front end, you'll also need node, npm, gulp and jspm installed.

Of course, once you're ready to transition to using the containerized setup, your project requires **zero** changes.  If you are working with other developers who are using the containerized setup, they won't have to do a thing.


### Staying in Sync

If you'd like to be able to update your project with changes from drydock, you can set it up as an upstream remote with the following commands:

```
git remote add drydock git@github.com:atrauzzi/laravel-drydock.git
git config remote.drydock.pushurl "Don't push to drydock from projects!"
```

The second command will ensure that you don't accidentally end up trying to push anything from your project to this repository.  Hardly a risk...unless you're me. :)


## Meta

After spending lots of time with various PaaS offerings, it dawned on me that they ultimately disappoint.  I've had to support a few teams now attempting to use
services like Google Cloud, Azure Web Sites and Amazon Elastic Beanstalk.  Each platform has a way of saddling my team with cumbersome proprietary environment quirks that swallow hours of productivity.
Eventually, you end up forced to make compromises and additions to your project that end up feeling a lot like technical debt.

The core issue is that all PaaS offerings are in some way are restricting the runtime, thus breaking PHP and the ecosystem of libraries we enjoy:

 - Inability to write to the local filesystem (only reason I care about this is for cached templates and optimizations)
 - Non-standard customizations to the runtime - especially when it comes to CURL
 - Limited database, cache, filesystem or other supporting technology choices
 - Confusing pricing structures that lead to high bills
 - Not running on the operating system of your choice
 - Brittle support library requirements
 - Obscure DNS and socket limitations
 - Missing PHP extensions
 
We know why they do it - it's to offer scalability!  But when we unpack the excuses, it tends to be that the PaaS vendors go too far.

### Docker for Windows and OSX
On March 24th, 2016, Docker announced [Docker for OSX and Windows](https://beta.docker.com/).  Head to that link and apply for the beta, it should greatly improve the Docker development experience on proprietary platforms.  
This tool is intended to supplant Docker Toolbox and leverages native virtualization on each platform to deliver the tightest possible experience.

### Why Laravel?

Due to the extensive number of abstractions it offers, Laravel in many ways can itself be considered a PaaS toolkit that targets PHP!
So long as you are gentle and configure it correctly, it can intelligently adapt to a huge variety of configurations and environments.

In the future, I would like to author an alternate version of Drydock that features [ASP.NET Core](http://live.asp.net) as the runtime.

### Deploying

When you're done authoring your project locally, you're ready to master a snapshot of your project as two docker images.  Use (and adapt) the following commands
as needed:

```
 ./run composer update --no-dev --prefer-dist --optimize-autoloader
 ./run npm install
 ./run jspm install
 ./run gulp
 
docker --log-level=debug build --force-rm --no-cache --pull --file=php.Dockerfile --tag=atrauzzi/laravel-drydock:php .
docker --log-level=debug build --force-rm --no-cache --pull --file=nginx.Dockerfile --tag=atrauzzi/laravel-drydock:nginx .
```

This will prepare two images in your local docker image cache that contain your entire project, ready to run!  Keep in mind that most docker registries require that images follow a specific naming convention.  Be sure to substitute `atrauzzi/laravel-drydock` above with names that correspond to your own registry.

If you're using a [registry](https://docs.docker.com/registry/) that supports docker's `push` command, distributing your images is easy: 

```
docker push atrauzzi/laravel-drydock:webapp
docker push atrauzzi/laravel-drydock:web
```

Again, please remember to substitute your own registry names above.  If you don't have a registry yet, I highly recommend [Docker Hub](https://hub.docker.com/).

# Laravel Drydock

This project is a premade, easy to use local development setup to be used for authoring Laravel applications.

The deliverables of this project are two docker containers running your project via [nginx](https://www.nginx.com/) and [php-fpm](http://php.net/manual/en/install.fpm.php).


## Usage

Please be sure to be using the most current versions of docker (>= 1.10) and docker-compose (>= 1.6).  If you're encountering any issues, this would be a good first thing to check.

Before getting started, be sure to check the platform specific notes below.  After that:

 - Ensure that the environment variable `UID` has been exported.  I suggest adding `export UID` in your default profile
 - Copy the example environment file to a new `.env` file, be sure to provide a valid app key.
 - `./run composer install` (you will likely encounter github's rate limit)
 - `./run artisan migrate` (although the demo doesn't rely on any models)
 - `./run npm install`
 - `./run jspm install`
 - run `docker-compose up`

These are going to download a considerable amount of dependencies for the front and back end, so be prepared to wait a little.

If you need to run any commands like `composer` or `artisan`, simply prefix them with `./run` at the root of this project.  They will be run inside the environment.

Once the environment is running, it will output all server requests, database access, queue access and cache messages.  Repeated queue messages are normal and are just the queue worker polling, the worker may error out a few times until RabbitMQ is fully started.


## What's in the box?

Most importantly?  Everything is **standard**!  That means you shouldn't need to be aware of platform or hosting specific quirks.  The default docs for the various projects should always be sufficient.

 - PHP 5.6.*
 - Laravel 5.2.*
 - Postgres 9.5.*
 - Redis 3.0.*
 - RabbitMQ 3.6.*

I've thrown together some inline controllers in `routes.php` that you can use to see the environment and verify that the queues are working.

### Local Development

The following ports are exposed to the host:

 - Laravel, 8080
 - Postgres, 5432
 - Redis, 6379
 - RabbitMQ Management, 8081
 - Maildev, 8082

This gives you the convenience of being able to run local GUI tools against the environment - I recommend PhpStorm, DataGrip and Redis Desktop.

Port 8082 is running an email trap that will intercept all outbound emails and show them in a convenient interface.  The project's default exception handler has also been slightly 
customized to render any exceptions to this email address.  This should be helpful while developing commands and background jobs.


#### Linux
I suggest installing docker-compose using pip so that you can always get the latest version.

After that, because Docker runs on Linux, consider yourself done!  Everything happens on localhost and is ready for you to use.

#### OSX

A current and working [docker toolbox](https://www.docker.com/products/docker-toolbox) installation should have you covered here.

If you would like to treat your development VM like a local machine, check out these [instructions on setting up NAT forwarding at the command line](https://www.virtualbox.org/manual/ch06.html#natforward).
You can also configure forwarded ports using the VirtualBox GUI using [the instructions found here](http://ask.xmodulo.com/access-nat-guest-from-host-virtualbox.html).

#### Windows
Due to major differences in the environment and filesystems between Windows and Linux, I've been avoiding using docker toolbox until they iron out some lingering bugs.

In the meantime, I've been using Ubuntu Server running on Hyper-V.  The basic idea is that you only use Windows for GUI tools and do everything else in a terminal connected the VM.
For getting standard SSH and a good POSIX terminal experience on Windows, I've been using [cmder](http://cmder.net) hosting an instance of [msys2](https://msys2.github.io) bash.

Additionally, I have some steps here to help with getting things set up as transparently as possible, so with a bit of effort, you should have something pleasant to use:

 - Set your VM up with an internal switch and configure Windows NAT to share your internet connection
 - Disable Windows Firewall for the internal switch connection
 - Accessing the Windows filesystem from the VM
    - Create `~/.cifs_credentials` on the VM, it's just two lines `username=` and `password=`
    - Run `mkdir /mnt/development` on the VM
    - Enable file sharing for the desired folder on the host
    - Add the following to `/etc/fstab`
```
//[WINDOWSHOST]/[path]/[to]/[folder]  /mnt/development  cifs  defaults,credentials=/home/[you]/.cifs_credentials,_netdev,rw,iocharset=utf8,soft,uid=1000,gid=100 0 0
```
 - You can pass traffic from localhost into the VM by running this on the Windows host:
```
netsh interface portproxy add v4tov4 listenport=[port] connectaddress=[VM's IP address] connectport=[port] protocol=tcp
```

I don't make any excuses for how involved these steps are.  But if you follow them, they should yield you a good day-to-day setup when dealing with PHP and Docker on Windows.
Once docker compose has sorted out the last few lingering issues with path translation and bind mounting, I'll update these instructions.


## Meta

After spending lots of time with various PaaS offerings, it has dawned on me that each one ultimately disappoints.  I've had to support a few teams now attempting to use 
services like Google Cloud, Azure and AWS.  Each platform has a way of saddling my team with cumbersome proprietary environment quirks that could swallow hours of productivity. 
Eventually, you end up forced to make compromises and additions to your project that end up feeling a lot like technical debt.

The core issue is that all PaaS in some way are restricting the runtime and breaking PHP and the ecosystem of libraries we enjoy:

 - Inability to write to the local filesystem (only reason I care about this is for cached templates and optimizations)
 - Non-standard customizations to the runtime - especially when it comes to CURL
 - Limited database, cache, filesystem or other supporting technology choices
 - Confusing pricing structures that lead to high bills
 - Not running on the operating system of your choice
 - Brittle support library requirements
 - Obscure DNS and socket limitations
 - Missing PHP extensions
 
We know why they do it - it's to offer scalability!  But when we unpack the excuses, it tends to be that the PaaS vendors go too far.


### Why Laravel?

Due to the extensive number of abstractions it offers, Laravel in many ways can itself be considered a PaaS toolkit that targets PHP!
So long as you are gentle and configure it correctly, it can intelligently adapt to a huge variety of configurations.

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

This will prepare two images in your local docker image cache that contain your entire project, ready to run!  Keep in mind that most docker registries require that images 
follow a specific naming convention.  Be sure to substitute `atrauzzi/laravel-drydock` above with names that correspond to your own registry.

If you're using a [registry](https://docs.docker.com/registry/) that supports docker's `push` command, distributing your images is easy: 

```
docker push atrauzzi/laravel-drydock:webapp
docker push atrauzzi/laravel-drydock:web
```

Again, please remember to substitute your own registry names above.  If you don't have a registry yet, I highly recommend [Docker Hub](https://hub.docker.com/).

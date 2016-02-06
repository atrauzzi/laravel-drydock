# Laravel DIY-PAAS Preview

After spending lots of time with various PaaS offerings, it has dawned on me that each one ultimately disappoints.  
In one way or another, you end up forced to make compromises and additions to your project that end up feeling a lot like technical debt.

The core issue is that all PaaS in some way are restricting the runtime and breaking PHP and the ecosystem of libraries we enjoy:

 - Non-standard customizations to the runtime - especially when it comes to CURL
 - Not running on the operating system of your choice
 - Missing PHP extensions
 - Overpriced
 - Brittle support library requirements
 - Obscure DNS and socket limitations
 - Limited database, cache, filesystem or other supporting technology choices
 - Inability to write to the local filesystem (only reason I care about this is for cached templates and optimizations)

We know why they do it - it's to offer scalability!  But when we unpack the excuses, it tends to be that the PaaS vendors go too far.


## Laravel

Believe it or not, due to the extensive abstractions offered, Laravel in many ways can itself be considered a PaaS toolkit that targets PHP!
So long as you are gentle and configure it correctly, it can intelligently adapt to a huge variety of configurations.

It's just a matter of orchestrating them together!


## Usage

Local development is always a challenge to get right.  Especially if you're using multiple software packages together.

To get started, perform the following:

 - Run `export $UID` in the terminal you will be launching from.
 - Copy the example environment file to a new `.env` file, be sure to provide a valid app key.
 - `./run composer install` (you will likely encounter git's rate limit)
 - run `docker-compose up`

If you need to run any commands like `composer` or `artisan`, simply prefix them with `./run` at the root of this project.  They will be run inside the environment.

Once the environment is running, it will output all server requests, database access, queue access and cache messages.  Repeated queue messages are normal and are just the queue worker polling, the worker may error out a few times until RabbitMQ is fully started.


## What's in the box?

Most importantly?  Everything is **standard**!  That means you shouldn't need to be aware of platform or hosting specific quirks.  The default docs for the various projects should always be sufficient.

 - Laravel 5.2
 - Postgres
 - Redis
 - RabbitMQ

I've thrown together some inline controllers in `routes.php` that you can use to see the environment and verify that the queues are working.

### Local Development

The following ports are exposed by default:

 - Laravel, 8080
 - Postgres, 5432
 - RabbitMQ Management, 8081
 - Redis, 6379

This gives you the convenience of being able to run local GUI tools against the environment - I recommend DataGrip and Redis Desktop.


#### Linux
Because Docker runs on Linux, consider yourself done!  Everything is on localhost.


#### Windows
Due to major differences in the environment and filesystems between Windows and Linux, I've been avoiding using docker compose until they iron out some lingering bugs.

In the meantime, I've been using Ubuntu Server running on Hyper-V.  The basic idea is that you only use Windows for GUI tools and do everything else in a terminal on the VM.
I do have some steps here to help with getting things set up as transparently as possible, so with a bit of effort, you should have something pleasant to use.

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

I don't make any excuses for how involved these steps are.  But if you follow them, they should yield you the most hassle-free setup when dealing with PHP and Docker.
Once docker compose has sorted out the last few lingering issues with path translation and bind mounting, the most we'll need is the `netsh` line.


#### OSX

I haven't done extensive testing on OSX yet, although docker compose seems to run best here.

If you would like to treat your development VM like a local machine, check out these [instructions on setting up NAT forwarding at the command line](https://www.virtualbox.org/manual/ch06.html#natforward).
You can also configure forwarded ports using the VirtualBox GUI using [the instructions found here](http://ask.xmodulo.com/access-nat-guest-from-host-virtualbox.html).
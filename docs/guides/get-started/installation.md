## Installation

### Before Installation:

Before you can install and use enhavo, your local environment must
fulfill some (really just some) basic requirements. First of all, you
need the composer, a dependency management tool, in php, downloadable at
[getcomposer.org](https://getcomposer.org/download/.)

The second necessary tool is "yarn", another powerful JavaScript
dependency management tool, which you will find on [this
page](https://yarnpkg.com/en/.)

That's it! After you have installed these two tools, your system is
ready for action.

### Install Enhavo within 5 Minutes.

The Enhavo App Edition only contains basic admin features, use this, if
your application is not used without standard content management
features to create a project with enhavo, you just need to run the
following composer command.

```bash
$ composer create-project enhavo/enhavo-app project-name dev-master
```

Now, only a few terminal-commands are left (take care, that you are in
your created project folder dir for all following commands). Use

```bash 
$ yarn install
```

for the installation of all JavaScript dependencies managed by yarn.
With

```bash 
$ yarn encore dev
```

you will compile your assets once to a single final app.js-File which
includes everything your app needs (Vue.js, Sass, TypeScript etc.)

Yarn is also responsible for managing the project\'s routes. You have to
update them after each route-change with the following command:

```bash 
$ yarn routes:dump
```

Now you need to create the configuration file. Just create a file with
the name `.env.local` in your project dir. Paste the following content
and edit your database setting.

``` 
APP_ENV=dev
APP_DEBUG=true
DATABASE_URL=mysql://root:root@127.0.0.1:3306/enhavo
```

Make sure your database exists or create it by following command

```bash 
$ bin/console doctrine:database:create
```

Now you need to create the database schema

```bash 
$ bin/console doctrine:schema:update --force
```

The finale installation steps are initializing Enhavo once and creating
your first backend user account with super-admin permissions.

```bash 
$ bin/console enhavo:init
$ bin/console fos:user:create my@email.com my@email.com password --super-admin
```

### Launching Project

So far, so good. The installation is complete and you\'re ready to
launch your empty base-application.

You can run this project on any webserver (like apache, nginx, etc.),
but for testing reasons, the fastest way to start your application for
the first time is using the PHP´s build-in web-server.

Start that build-in server with

```bash 
$ php bin/console server:run
```

and see the result in your browser under `http://127.0.0.1:8000/admin`

Use the username and password from the user account, you´ve created
before with `fos:user:create`, to log in.

### Final Words

Great! We\'ve installed the basic, really basic, enhavo CMS with our two
awesome dependency management tools composer and yarn.

### Well-intentioned Advices

-   During the complete developing process, it´s better to recompile
    assets automatically when files change, to do that, use:

```bash 
$ yarn encore dev --watch
```

-   If you want to launch your application with any other web server,
    use the `~/PathToYourProject/YourProject/public` - Folder as your
    document root.

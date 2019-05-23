![alt text](assets/enhavo/images/enhavo.svg "enhavo")
<br/>
<br/>

[![License](https://img.shields.io/packagist/l/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)
[![Build status...](https://api.travis-ci.org/enhavo/enhavo.svg?branch=master)](https://travis-ci.org/enhavo/enhavo)
[![Codefresh build status]( https://g.codefresh.io/api/badges/pipeline/gseidel/enhavo%2Fenhavo%2Fenhavo?type=cf-1)]( https://g.codefresh.io/public/accounts/gseidel/pipelines/enhavo/enhavo/enhavo)
[![Scrutinizer](https://scrutinizer-ci.com/g/enhavo/enhavo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/enhavo/enhavo)
[![Coverage](https://scrutinizer-ci.com/g/enhavo/enhavo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/enhavo/enhavo)
[![Version](https://img.shields.io/packagist/v/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)


The enhavo CMS is a open source PHP project based on the fullstack Symfony framework and uses awesome Sylius components
to serve a very flexible software, that can handle most of complex data structure with a clean and usability interface.

Contribute
----------

This is the main repository of the enhavo project. If you want to contribute you need to checkout this repository.
After checkout out just use the installer to create the database and the admin user.

```bash
$ composer install
$ yarn install
$ yarn encore dev
$ bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
$ bin/console enhavo:install
```

Editions
--------

If you want to use enhavo CMS, we recommend to use one of the enhavo editions:

* [Enhavo CMS](https://github.com/enhavo/enhavo-cms) Contians all CMS relavent feature
* [Enhavo Shop](https://github.com/enhavo/enhavo-shop) Same as CMS but also contain Shop features
* [Enhavo App](https://github.com/enhavo/enhavo-app) Contains only basic Admin features

Demo
----

Use username **admin@enhavo.com** with password **admin** to log in into the backend.

[demo.enhavo.com](http://demo.enhavo.com/admin/login)

Docker
------

To run the demo with docker you can use following command

```bash
$ docker run -d -e DATABASE_URL='mysql://root:root@mysql:3306/enhavo' --link 'mysql:mysql' -p '80:80' enhavo/enhavo:master
```

Documentation
-------------

Documentation is available at [docs.enhavo.com](http://docs.enhavo.com).

Compile the documentation with

```bash
$ pip install -U Sphinx
$ sphinx-build -b html docs/source build/docs
```
Or use the autobuilder

```bash
$ pip install sphinx-autobuild
$ sphinx-autobuild docs/source build/docs
```

Run tests
---------

First setup the test database for behat testing, with

```bash
$ app/console doctrine:schema:update --force --env="test"
```

Then run the test itself.

```bash
$ bin/behat
$ bin/phpunit
```

MIT License
-----------

License can be found [here](https://github.com/enhavo/enhavo/blob/master/LICENSE).
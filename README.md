![alt text](assets/enhavo/images/enhavo.svg "enhavo")
<br/>
<br/>

[![License](https://img.shields.io/packagist/l/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)
[![Build status...](https://api.travis-ci.org/enhavo/enhavo.svg?branch=master)](https://travis-ci.org/enhavo/enhavo)
[![Scrutinizer](https://scrutinizer-ci.com/g/enhavo/enhavo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/enhavo/enhavo)
[![Coverage Status](https://coveralls.io/repos/github/enhavo/enhavo/badge.svg?branch=master)](https://coveralls.io/github/enhavo/enhavo?branch=master)
[![Version](https://img.shields.io/packagist/v/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)


The enhavo CMS is a open source PHP project on top of the fullstack Symfony framework and uses awesome Sylius components
to serve a very flexible software, that can handle most of complex data structure with a clean and usability interface.

Enhavo is still under heavy development and we can't gurantee for backward compatibility or security issues nor is our documentation up to date. 
So we advice you to not use the software for production until we reach a stable release. 


Contribute
----------

Help us to develop the software. This is the main repository of the enhavo project. 
Feel free to open tickets or pull requests or just give us feedback.
If you are a github user, you can star our project.

Install
-------

For install enhavo, you need `composer` and `yarn`

```bash
$ composer install
$ yarn install
$ yarn encore dev
$ yarn routes:dump
$ bin/console enhavo:init
$ bin/console fos:user:create my@email.com my@email.com password --super-admin
```

Editions
--------

If you want to use enhavo CMS, we recommend to use one of the enhavo editions:

* [Enhavo CMS](https://github.com/enhavo/enhavo-cms) Contais CMS relavent feature
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

Documentation is available at [docs.enhavo.com](http://docs.enhavo.com). To create documentation you need
`sphinx`. Install it over `pip` with 

```bash
$ pip install -U Sphinx
```

Compile the documentation with

```bash
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
$ bin/console doctrine:schema:update --force --env="test"
```

Then run the test itself.

```bash
$ bin/behat
$ ./phpunit
```

MIT License
-----------

License can be found [here](https://github.com/enhavo/enhavo/blob/master/LICENSE).
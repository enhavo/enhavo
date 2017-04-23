[![License](https://img.shields.io/packagist/l/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)
[![Build status...](https://api.travis-ci.org/enhavo/enhavo.svg)](https://travis-ci.org/enhavo/enhavo)
[![Scrutinizer](https://scrutinizer-ci.com/g/enhavo/enhavo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/enhavo/enhavo)
[![Coverage](https://scrutinizer-ci.com/g/enhavo/enhavo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/enhavo/enhavo)
[![Version](https://img.shields.io/packagist/v/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)
[![Dependency Status](https://www.versioneye.com/user/projects/56aa8a367e03c700377df5b0/badge.svg)](https://www.versioneye.com/user/projects/56aa8a367e03c700377df5b0)
[![Documentation Status](https://readthedocs.org/projects/enhavo/badge/?version=latest)](http://enhavo.readthedocs.org/en/latest/?badge=latest)



enhavo
------

The enhavo CMS is a open source PHP project based on the fullstack Symfony framework and uses awesome Sylius components
to serve a very flexible software, that can handle most of complex data structure with a clean and usability interface.

Quick Installation
------------------

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar create-project enhavo/enhavo-cms enhavo
```

Demo
----

Use username **admin** with password **admin** to log in into the backend.

[demo.enhavo.com](http://demo.enhavo.com/admin/login)

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
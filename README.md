![alt text](assets/enhavo/images/enhavo.svg "enhavo")
<br/>
<br/>

[![License](https://img.shields.io/packagist/l/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)
[![Continuous Integration](https://github.com/enhavo/enhavo/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/enhavo/enhavo/actions/workflows/ci.yml)
[![Scrutinizer](https://scrutinizer-ci.com/g/enhavo/enhavo/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/enhavo/enhavo)
[![Code Coverage](https://scrutinizer-ci.com/g/enhavo/enhavo/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/enhavo/enhavo/?branch=master)
[![Version](https://img.shields.io/packagist/v/enhavo/enhavo.svg)](https://packagist.org/packages/enhavo/enhavo)

The enhavo CMS is a open source PHP project on top of the fullstack Symfony framework and uses awesome Sylius components
to serve a very flexible software, that can handle most of complex data structure with a clean and usability interface.

Enhavo is still under heavy development and we can't guarantee for backward compatibility or security issues nor is our documentation up to date. 
So we advice you to not use the software for production until we reach a stable release. 

Get started
-----------

If you just want to use enhavo, you don't need to install this repository. 
Read the [Get Started](https://docs.enhavo.com/get-started/index.html) tutorial to install your own enhavo application.

Demo
----

If you want to check the enhavo look and feel. Take a look at our demo on [demo.enhavo.com](http://demo.enhavo.com/admin/login)

| User            | Password      |
|-----------------|---------------|
| admin@enhavo.com|  admin        |

Contribute
----------

Help us to develop the software. This is the main repository of the enhavo project. 
Feel free to open tickets or pull requests or just give us feedback.
If you are a github user, you can star our project.

----------------------

If you want to contribute code, you need to run the main repository. Make sure you have installed `composer` and `yarn` on 
your local machine. Fork and clone the repo and add a ``.env.local`` file containing your database credentials.

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/enhavo
```

And a test config ``.env.test.local`` containing your test database credentials.

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/enhavo_test
```

Execute following commands on your shell:

```bash
$ composer install
$ yarn install
$ yarn encore dev
$ yarn routes:dump
$ bin/console doctrine:database:create
$ bin/console doctrine:database:create --env=test
$ bin/console doctrine:migrations:migrate
$ bin/console doctrine:migrations:migrate --env=test
$ bin/console enhavo:init
$ bin/console enhavo:user:create --super-admin
```

Make your changes and run the tests.

```bash
$ bin/phpunit
$ bin/behat
$ bin/mocha
$ bin/karma
```

**Testing stack**

Depending on what you are going to test, choose the right tool.

```
<----------------------------- Behat ---------------------------------------->
<------------- PHPUnit ---------------->
                                        <------------- Karma ---------------->
                                        <------ Mocha --->
[**** Database ****][**** PHP File ****][**** JS File ****][**** Browser ****]
```


Documentation
-------------

The documentation is available at [docs.enhavo.com](http://docs.enhavo.com). 

----------------------

If you want to contribute to the documentation you need `sphinx`. 
Read [sphinx-doc.org](https://www.sphinx-doc.org/en/master/usage/installation.html) to install it.
For the ```--watch``` option you have to [install](https://pypi.org/project/sphinx-autobuild/) `sphinx-autobuild`.

Fork and clone this repository and make your changes under `docs/source`. After each change run following command
and open the `build/docs/index.html` in your browser to see and valid your changes.

```bash
$ bin/docs
$ bin/docs --watch
```

MIT License
-----------

License can be found [here](https://github.com/enhavo/enhavo/blob/master/LICENSE).

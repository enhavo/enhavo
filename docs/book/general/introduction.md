# Introduction

## Symfony based

Enhavo is based on the fullstack Symfony framework. When we started to
create enhavo, Symfony 2.2 was the current release.

We wanted to build our CMS on a good framework, and there are a lot of
php frameworks out there. After some research and tests, we decided to
build it on top of Symfony, because we liked the modular approach and
the awesome dependency injection. So we decided to go on with the
Symfony philosophy and build our CMS features as modules, and then build
more modules based on the modules we already created.

If you are familiar with Symfony and its structure, you will feel
comfortable working on enhavo.

## Sylius based

When we say \"enhavo is based on Sylius\", what we mean is that enhavo
is based on the SyliusResourceBundle. If you want to know more about
this Bundle, you can read the Sylius
[documentation](https://docs.sylius.com/en/1.6/components_and_bundles/bundles/SyliusResourceBundle/).

The SyliusResourceBundle provides a very abstract and powerful CRUD
Controller. This frees us from having to implement our own CRUD
Controllers in our modules, and we can focus on the models and forms
instead. A lot of the configuration is done in the definition of the
routes. This makes sense to us, because the route definition is the
entry point of every request in the application. It defines the
controller action we want use, so why not inject more configuration here
and skip the controller completely.

## Bring your own domain model

The core functionality of enhavo provides a user interface with a user
friendly grid and form. The core doesn't know anything about concrete
models. Most of the bundles have no idea how a page or an article look
like. Usually those are the classic models a CMS depends on. We have
suggestion for the structures of a page and its contents, of course, but
you don't need to use those if you don\'t want to. You can bring your
own model instead, and enhavo is helping you to manage it.

We believe that a good CMS should know the model, and not the model
should know the CMS. This is a very big difference and has an impact on
your whole architecture. For you that means that you can easily switch
the CMS, because a lot of the functionality has no dependency to enhavo.
This approach is the central statement of domain driven design. Keep
your domain away from dependencies that you don't control! You have
already isolated your domain? Just bring it to enhavo, again, we provide
you a powerfully user interface and functionality that may be useful for
you.

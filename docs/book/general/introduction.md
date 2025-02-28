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

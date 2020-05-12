Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/newsletter-bundle ^0.8


.. include:: /book/_includes/installation/dependencies.rst

* :doc:`BlockBundle </book/block-bundle/installation>`
* :doc:`FormBundle </book/form-bundle/installation>`


.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/newsletter


.. include:: /book/_includes/installation/register-action-package.rst

.. code:: ts

    // import statement
    import NewsletterActionRegistryPackage from "@enhavo/newsletter/Action/ActionRegistryPackage";

    // register the package
    this.registerPackage(new NewsletterActionRegistryPackage(application));

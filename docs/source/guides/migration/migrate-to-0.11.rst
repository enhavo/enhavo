Migrate to 0.11
==============

.. rubric:: 1. assets/enhavo folder

* Remove assets/services
* Add Entrypoints to assets/enhavo/entrypoint*
* DI Container to assets/enhavo/container.yaml with d.ts file
* Change tsconfig es5 -> es6
* Add babelconfig .babelrc

.. rubric:: 2. Remove swiftmailer

* Remove swiftmailer from bundles.php
* Remove swiftmailer configs

.. rubric:: 3. Check Events

* Use common ResourceEvents instead of specific sylius events (Should use only if controller based)

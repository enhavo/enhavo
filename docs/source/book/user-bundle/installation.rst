Installation
============

.. include:: /book/_includes/installation/composer.rst

.. code::

  $ composer require enhavo/user-bundle ^0.8

.. include:: /book/_includes/installation/dependencies.rst

* :doc:`FormBundle </book/form-bundle/installation>`

.. include:: /book/_includes/installation/node-package.rst

.. code::

  $ yarn add @enhavo/user

.. include:: /book/_includes/installation/register-encore-package.rst

.. code::

  // import
  const UserPackage = require('@enhavo/user/Encore/EncoreRegistryPackage');

  // register package
  .register(new UserPackage());


.. include:: /book/_includes/installation/change-configuration.rst

Update your ``config/packages/security.yaml``

.. code:: yaml

  security:
      encoders:
          FOS\UserBundle\Model\UserInterface: sha512

      providers:
          fos_userbundle:
              id: fos_user.user_provider.username

      firewalls:
          dev:
              pattern:  ^/(_(profiler|wdt)|css|images|js)/
              security: false

          admin:
              pattern:  ^/admin/.*
              context: user
              form_login:
                  provider:       fos_userbundle
                  login_path:     /admin/login
                  use_forward:    false
                  check_path:     /admin/login/check
                  failure_path:   /admin/login
                  default_target_path: /admin
              logout:
                  path:   /admin/logout
                  target: /admin/login
              anonymous:  true

          user:
              pattern: ^/user/.*
              context: user
              form_login:
                  provider:       fos_userbundle
                  login_path:     /user/login
                  use_forward:    false
                  check_path:     /user/login/check
                  failure_path:   /user/login
                  default_target_path: /user/profile
              logout:
                  path:   /user/logout
                  target: /user/login
              anonymous:  true

          main:
              pattern: .*
              context: user
              form_login:
                  provider:       fos_userbundle
                  login_path:     /user/login
                  use_forward:    false
                  check_path:     /user/login/check
                  failure_path:   /user/login
                  default_target_path: /user/profile
              logout:
                  path:   /user/logout
                  target: /
              anonymous:  true

      access_control:
          - { path: ^/admin/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
          - { path: ^/admin/login/check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
          - { path: ^/admin/reset-password, role: IS_AUTHENTICATED_ANONYMOUSLY }
          - { path: ^/admin/, role: ROLE_ADMIN }

          - { path: ^/user/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
          - { path: ^/user/login/check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
          - { path: ^/user/registration, role: IS_AUTHENTICATED_ANONYMOUSLY }
          - { path: ^/user/reset-password, role: IS_AUTHENTICATED_ANONYMOUSLY }
          - { path: ^/user/, role: ROLE_USER }

Update your ``config/packages/enhavo.yaml``

.. code:: yaml

    enhavo_app:
        menu:
            user:
                type: user

.. include:: /book/_includes/installation/migrate-database.rst

.. include:: /book/_includes/installation/build-assets.rst

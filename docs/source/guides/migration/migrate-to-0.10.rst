Migrate to 0.10
==============

.. rubric:: 1. Change asset path to lowercase (Classname is still CamelCase)

Check all your file under ``assets`` and your `webpack.config.js``

.. code-block:: js

    const EnhavoEncore = require('@enhavo/core/EnhavoEncore');
    const AppPackage = require('@enhavo/app/encore/AppPackage');
    const AppThemePackage = require('@enhavo/app/encore/AppThemePackage');
    const FormPackage = require('@enhavo/form/encore/FormPackage');


.. rubric:: 2. Change your ``security.yaml``

.. code-block:: yaml

    security:
        encoders:
            Symfony\Component\Security\Core\User\User: plaintext
            Enhavo\Bundle\UserBundle\Model\UserInterface: sha512

        providers:
            entity_user_provider:
                entity:
                    class: Enhavo\Bundle\UserBundle\Model\User

        firewalls:
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false

            admin:
                user_checker: Enhavo\Bundle\UserBundle\Security\UserChecker
                guard:
                    authenticators:
                        - Enhavo\Bundle\UserBundle\Security\Authentication\FormLoginAuthenticator
                pattern:  ^/admin/.*
                context: user
                form_login:
                    provider:       entity_user_provider
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
                user_checker: Enhavo\Bundle\UserBundle\Security\UserChecker
                guard:
                    authenticators:
                        - Enhavo\Bundle\UserBundle\Security\Authentication\FormLoginAuthenticator

                pattern: ^/user/.*
                context: user
                form_login:
                    provider:       entity_user_provider
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
                user_checker: Enhavo\Bundle\UserBundle\Security\UserChecker
                form_login:
                    provider:       entity_user_provider
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
            - { path: ^/admin/reset-password/.+, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/admin/, role: ROLE_ADMIN }

            - { path: ^/user/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/user/login/check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/user/registration/.+, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/user/reset-password/.+, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/user/, role: ROLE_USER }

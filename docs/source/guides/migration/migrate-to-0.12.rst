Migrate to 0.12
===============

.. rubric:: 1. config/routes/enhavo_user.yaml file

    enhavo_user_theme:
        resource: "@EnhavoUserBundle/Resources/config/routes/theme/*"
        prefix: /

.. rubric:: 2. config/packages/security.yaml file

    security:
        enable_authenticator_manager: true
        # ... other configs
        firewalls:
            # ... other firewalls
            # user firewall can be removed

            admin:
                pattern:  ^/admin/?.*
                context: user
                user_checker: Enhavo\Bundle\UserBundle\Security\UserChecker
                entry_point: Enhavo\Bundle\UserBundle\Security\EntryPoint\FormAuthenticationEntryPoint
                custom_authenticators:
                    - Enhavo\Bundle\UserBundle\Security\Authentication\FormLoginAuthenticator
                logout:
                    path: enhavo_user_security_logout
                    target: enhavo_user_security_login
                # form_login, anonymous and guard must be deleted

            main:
                pattern: .*
                context: user
                user_checker: Enhavo\Bundle\UserBundle\Security\UserChecker
                entry_point: Enhavo\Bundle\UserBundle\Security\EntryPoint\FormAuthenticationEntryPoint
                custom_authenticators:
                    - Enhavo\Bundle\UserBundle\Security\Authentication\FormLoginAuthenticator
                logout:
                    path: enhavo_user_theme_security_logout
                    target: enhavo_user_theme_security_login
                # form_login, anonymous and guard must be deleted

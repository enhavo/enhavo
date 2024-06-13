## Installation

Install Packages

```bash
$ composer require enhavo/user-bundle
```

```bash
$ yarn add @enhavo/user
```


Update your `config/packages/vue.yaml`

```yaml
enhavo_app:
    vue:
        route_providers:
            # ... 
            admin: // [!code ++]
                type: routing // [!code ++]
                groups: ['admin'] // [!code ++]
```




Update your `config/packages/security.yaml`

```yaml
security:
    password_hashers:
        Enhavo\Bundle\UserBundle\Model\UserInterface: auto  // [!code ++]

    providers:
        entity_user_provider:
            entity:
                class: Enhavo\Bundle\UserBundle\Model\User  // [!code ++]

    firewalls:
        # ...
        admin: // [!code ++]
            pattern: ^/admin/?.* // [!code ++]
            context: user // [!code ++]
            user_checker: Enhavo\Bundle\UserBundle\Security\UserChecker // [!code ++]
            entry_point: Enhavo\Bundle\UserBundle\Security\EntryPoint\FormAuthenticationEntryPoint // [!code ++]
            custom_authenticators: // [!code ++]
                - Enhavo\Bundle\UserBundle\Security\Authentication\FormLoginAuthenticator // [!code ++]
            logout: // [!code ++]
                path: enhavo_user_security_logout // [!code ++]
                target: enhavo_user_security_login // [!code ++]

        main:
            pattern: .*
            context: user

    access_control:
        - { path: ^/admin/login$, role: PUBLIC_ACCESS } // [!code ++]
        - { path: ^/admin/api/login$, role: PUBLIC_ACCESS } // [!code ++]
        - { path: ^/admin/reset-password/.+, role: PUBLIC_ACCESS } // [!code ++]
        - { path: ^/admin/api/reset-password/.+, role: PUBLIC_ACCESS } // [!code ++]
        - { path: ^/admin/, role: ROLE_ADMIN } // [!code ++]
```

Update your `config/packages/enhavo.yaml`

```yaml
enhavo_app:
    menu:
        user: // [!code ++]
            type: user // [!code ++]
```

Add `enhavo_user.yaml` file to your routes folder `config/routes`

```yaml
# config/routes/enhavo_user.yaml
enhavo_user_admin:
    resource: "@EnhavoUserBundle/Resources/config/routes/admin/*"
    prefix: /admin

enhavo_user_theme:
    resource: "@EnhavoUserBundle/Resources/config/routes/theme/*"
    prefix: /
```

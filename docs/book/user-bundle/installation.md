## Installation

```bash
$ composer require enhavo/user-bundle
```

```bash
$ yarn add @enhavo/user
```

```ts 
// import
const UserPackage = require('@enhavo/user/Encore/EncoreRegistryPackage');

// register package
.register(new UserPackage());
```

Update your `config/packages/security.yaml`

```yaml
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
```

Update your `config/packages/enhavo.yaml`

```yaml
enhavo_app:
    menu:
        user:
            type: user
```

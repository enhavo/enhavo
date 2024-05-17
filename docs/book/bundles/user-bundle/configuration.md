## Configuration

### Register configuration

```yaml
registration_register:
    template: theme/security/registration/register.html.twig
    redirect_route: enhavo_user_theme_registration_check
    confirmation_route: enhavo_user_theme_registration_confirm
    auto_login: true
    auto_enabled: true
    auto_verified: false
    mail:
        template: mail/security/registration.html.twig
        subject: registration.mail.subject
        sender_name: ~
        content_type: text/plain
    translation_domain: 'EnhavoUserBundle'
    form:
        class: Enhavo\Bundle\UserBundle\Form\Type\RegistrationType
        options: [] # "register" validation group is added automatically
```

```yaml
registration_check:
    template: theme/security/registration/check.html.twig
```

```yaml
registration_confirm:
    template: 'theme/security/registration/confirm.html.twig'
    mail:
        template: 'mail/security/confirmation.html.twig'
        subject: 'confirmation.mail.subject'
    translation_domain: 'EnhavoUserBundle'
    redirect_route: 'enhavo_user_theme_registration_finish'
```

```yaml
registration_finish:
    template: 'theme/security/registration/finish.html.twig'
```

### Profile configuration

```yaml
profile:
    template: theme/resource/user/profile.html.twig
    form:
        class: Enhavo\Bundle\UserBundle\Form\Type\ProfileType
        options: []
```

### Reset password configuration 

```yaml
reset_password_request:
    template: theme/security/reset-password/request.html.twig
    mail_template: mail/security/reset-password.html.twig
    mail_subject: reset_password.mail.subject
    translation_domain: EnhavoUserBundle
    redirect_route: enhavo_user_theme_reset_password_check
    confirmation_route: enhavo_user_theme_reset_password_confirm
    form:
        class: Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordRequestType
        options: []
```

```yaml
reset_password_check:
    template: 'theme/security/reset-password/check.html.twig'
```

```yaml
reset_password_confirm:
    auto_login: true
    template: 'theme/security/reset-password/confirm.html.twig'
    redirect_route': 'enhavo_user_theme_reset_password_finish'
    form:
        class: Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordType
        options: []
```

```yaml
reset_password_finish:
    template: 'theme/security/reset-password/finish.html.twig'
```

### Change email configuration

```yaml
change_email_request:
    template: theme/security/change-email/request.html.twig
    mail_template: mail/security/change-email.html.twig
    mail_subject: change_email.mail.subject
    translation_domain: EnhavoUserBundle
    redirect_route: enhavo_user_theme_change_email_check
    confirmation_route: enhavo_user_theme_change_email_confirm
    form:
        class: ChangeEmailRequestType::class
        options: []
```

```yaml
change_email_check:
    template: theme/security/change-email/check.html.twig
```

```yaml
change_email_confirm:
    template: theme/security/change-email/confirm.html.twig
    redirect_route: enhavo_user_theme_change_email_finish
    form:
        class: ResetPasswordType::class
        options: []
```

```yaml
change_email_finish:
    template: theme/security/change-email/finish.html.twig
```

### Login configuration

```yaml
login:
    template: theme/security/login.html.twig
    redirect_route: enhavo_user_theme_user_profile
    route: enhavo_user_theme_security_login
    verification_required: true
    max_failed_login_attempts: 3
    password_max_age: 3 days
```

### Change password configuration

```yaml
change_password:
    form:
        class: ChangePasswordFormType::class
        options: []
```

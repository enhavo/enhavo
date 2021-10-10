
.. code-block:: yaml

  enhavo_user:
    resources:
        user:
            classes:
                form: App\Form\Type\Admin\UserType
                model: App\Entity\User

    theme:
        registration_register:


registration_register:
    template: theme/security/registration/register.html.twig
    redirect_route: enhavo_user_theme_registration_check
    confirmation_route: enhavo_user_theme_registration_confirm
    mail_template: mail/security/registration.html.twig
    mail_subject: registration.mail.subject
    translation_domain: 'EnhavoUserBundle'
    form:
        class: Enhavo\Bundle\UserBundle\Form\Type\RegistrationType
        options: []

registration_check:
    template: theme/security/registration/check.html.twig

registration_confirm:
    template: 'theme/security/registration/confirm.html.twig'
    mail_template: 'mail/security/confirmation.html.twig'
    mail_subject: 'confirmation.mail.subject'
    translation_domain: 'EnhavoUserBundle'
    redirect_route: 'enhavo_user_theme_registration_finish'

registration_finish:
    template: 'theme/security/registration/finish.html.twig'

profile:
    template: theme/resource/user/profile.html.twig
    form:
        class: Enhavo\Bundle\UserBundle\Form\Type\ProfileType
        options: []

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

reset_password_check:
    template: 'theme/security/reset-password/check.html.twig'

reset_password_confirm:
    auto_login: true
    template: 'theme/security/reset-password/confirm.html.twig'
    redirect_route': 'enhavo_user_theme_reset_password_finish'
    form:
        class: Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordType
        options: []

reset_password_finish:
    template: 'theme/security/reset-password/finish.html.twig'

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

change_email_check:
    template: theme/security/change-email/check.html.twig

change_email_confirm:
    template: theme/security/change-email/confirm.html.twig
    redirect_route: enhavo_user_theme_change_email_finish
    form:
        class: ResetPasswordType::class
        options: []

change_email_finish:
    template: theme/security/change-email/finish.html.twig

login:
    template: theme/security/login.html.twig
    redirect_route: enhavo_user_theme_user_profile
    route: enhavo_user_theme_security_login

change_password:
    form:
        class: ChangePasswordFormType::class
        options: []

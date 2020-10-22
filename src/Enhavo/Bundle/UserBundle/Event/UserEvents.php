<?php
/**
 * UserEvents.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\Event;


final class UserEvents
{
    const LOGIN = 'enhavo_user.login';

    /**
     * The CHANGE_PASSWORD_INITIALIZE event occurs when the change password process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent")
     */
    const CHANGE_PASSWORD_INITIALIZE = 'enhavo_user.change_password.edit.initialize';

    /**
     * The CHANGE_PASSWORD_SUCCESS event occurs when the change password form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FormEvent")
     */
    const CHANGE_PASSWORD_SUCCESS = 'enhavo_user.change_password.edit.success';

    /**
     * The CHANGE_PASSWORD_COMPLETED event occurs after saving the user in the change password process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterUserResponseEvent")
     */
    const CHANGE_PASSWORD_COMPLETED = 'enhavo_user.change_password.edit.completed';

    /**
     * The GROUP_CREATE_INITIALIZE event occurs when the group creation process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GroupEvent")
     */
    const GROUP_CREATE_INITIALIZE = 'enhavo_user.group.create.initialize';

    /**
     * The GROUP_CREATE_SUCCESS event occurs when the group creation form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FormEvent")
     */
    const GROUP_CREATE_SUCCESS = 'enhavo_user.group.create.success';

    /**
     * The GROUP_CREATE_COMPLETED event occurs after saving the group in the group creation process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterGroupResponseEvent")
     */
    const GROUP_CREATE_COMPLETED = 'enhavo_user.group.create.completed';

    /**
     * The GROUP_DELETE_COMPLETED event occurs after deleting the group.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterGroupResponseEvent")
     */
    const GROUP_DELETE_COMPLETED = 'enhavo_user.group.delete.completed';

    /**
     * The GROUP_EDIT_INITIALIZE event occurs when the group editing process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseGroupEvent")
     */
    const GROUP_EDIT_INITIALIZE = 'enhavo_user.group.edit.initialize';

    /**
     * The GROUP_EDIT_SUCCESS event occurs when the group edit form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FormEvent")
     */
    const GROUP_EDIT_SUCCESS = 'enhavo_user.group.edit.success';

    /**
     * The GROUP_EDIT_COMPLETED event occurs after saving the group in the group edit process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterGroupResponseEvent")
     */
    const GROUP_EDIT_COMPLETED = 'enhavo_user.group.edit.completed';

    /**
     * The PROFILE_EDIT_INITIALIZE event occurs when the profile editing process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent")
     */
    const PROFILE_EDIT_INITIALIZE = 'enhavo_user.profile.edit.initialize';

    /**
     * The PROFILE_EDIT_SUCCESS event occurs when the profile edit form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FormEvent")
     */
    const PROFILE_EDIT_SUCCESS = 'enhavo_user.profile.edit.success';

    /**
     * The PROFILE_EDIT_COMPLETED event occurs after saving the user in the profile edit process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterUserResponseEvent")
     */
    const PROFILE_EDIT_COMPLETED = 'enhavo_user.profile.edit.completed';

    /**
     * The REGISTRATION_INITIALIZE event occurs when the registration process is initialized.
     *
     * This event allows you to modify the default values of the user before binding the form.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const REGISTRATION_INITIALIZE = 'enhavo_user.registration.initialize';

    /**
     * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FormEvent")
     */
    const REGISTRATION_SUCCESS = 'enhavo_user.registration.success';

    /**
     * The REGISTRATION_FAILURE event occurs when the registration form is not valid.
     *
     * This event allows you to set the response instead of using the default one.
     * The event listener method receives a Enhavo\Bundle\UserBundle\Event\FormEvent instance.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FormEvent")
     */
    const REGISTRATION_FAILURE = 'enhavo_user.registration.failure';

    /**
     * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterUserResponseEvent")
     */
    const REGISTRATION_COMPLETED = 'enhavo_user.registration.completed';

    /**
     * The REGISTRATION_CONFIRM event occurs just before confirming the account.
     *
     * This event allows you to access the user which will be confirmed.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent")
     */
    const REGISTRATION_CONFIRM = 'enhavo_user.registration.confirm';

    /**
     * The REGISTRATION_CONFIRMED event occurs after confirming the account.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterUserResponseEvent")
     */
    const REGISTRATION_CONFIRMED = 'enhavo_user.registration.confirmed';

    /**
     * The RESETTING_RESET_REQUEST event occurs when a user requests a password reset of the account.
     *
     * This event allows you to check if a user is locked out before requesting a password.
     * The event listener method receives a Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_RESET_REQUEST = 'enhavo_user.resetting.reset.request';

    /**
     * The RESETTING_RESET_INITIALIZE event occurs when the resetting process is initialized.
     *
     * This event allows you to set the response to bypass the processing.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_RESET_INITIALIZE = 'enhavo_user.resetting.reset.initialize';

    /**
     * The RESETTING_RESET_SUCCESS event occurs when the resetting form is submitted successfully.
     *
     * This event allows you to set the response instead of using the default one.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FormEvent ")
     */
    const RESETTING_RESET_SUCCESS = 'enhavo_user.resetting.reset.success';

    /**
     * The RESETTING_RESET_COMPLETED event occurs after saving the user in the resetting process.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\FilterUserResponseEvent")
     */
    const RESETTING_RESET_COMPLETED = 'enhavo_user.resetting.reset.completed';

    /**
     * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
     *
     * This event allows you to access the response which will be sent.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const SECURITY_IMPLICIT_LOGIN = 'enhavo_user.security.implicit_login';

    /**
     * The RESETTING_SEND_EMAIL_INITIALIZE event occurs when the send email process is initialized.
     *
     * This event allows you to set the response to bypass the email confirmation processing.
     * The event listener method receives a Enhavo\Bundle\UserBundle\Event\GetResponseNullableUserEvent instance.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseNullableUserEvent")
     */
    const RESETTING_SEND_EMAIL_INITIALIZE = 'enhavo_user.resetting.send_email.initialize';

    /**
     * The RESETTING_SEND_EMAIL_CONFIRM event occurs when all prerequisites to send email are
     * confirmed and before the mail is sent.
     *
     * This event allows you to set the response to bypass the email sending.
     * The event listener method receives a Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_SEND_EMAIL_CONFIRM = 'enhavo_user.resetting.send_email.confirm';

    /**
     * The RESETTING_SEND_EMAIL_COMPLETED event occurs after the email is sent.
     *
     * This event allows you to set the response to bypass the the redirection after the email is sent.
     * The event listener method receives a Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent instance.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\GetResponseUserEvent")
     */
    const RESETTING_SEND_EMAIL_COMPLETED = 'enhavo_user.resetting.send_email.completed';

    /**
     * The USER_CREATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the created user and to add some behaviour after the creation.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const USER_CREATED = 'enhavo_user.user.created';

    /**
     * The USER_PASSWORD_CHANGED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the created user and to add some behaviour after the password change.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const USER_PASSWORD_CHANGED = 'enhavo_user.user.password_changed';

    /**
     * The USER_ACTIVATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the activated user and to add some behaviour after the activation.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const USER_ACTIVATED = 'enhavo_user.user.activated';

    /**
     * The USER_DEACTIVATED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the deactivated user and to add some behaviour after the deactivation.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const USER_DEACTIVATED = 'enhavo_user.user.deactivated';

    /**
     * The USER_PROMOTED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the promoted user and to add some behaviour after the promotion.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const USER_PROMOTED = 'enhavo_user.user.promoted';

    /**
     * The USER_DEMOTED event occurs when the user is created with UserManipulator.
     *
     * This event allows you to access the demoted user and to add some behaviour after the demotion.
     *
     * @Event("Enhavo\Bundle\UserBundle\Event\UserEvent")
     */
    const USER_DEMOTED = 'enhavo_user.user.demoted';
}

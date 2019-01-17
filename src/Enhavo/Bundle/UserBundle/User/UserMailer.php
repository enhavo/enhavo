<?php
/**
 * UserMailer.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\User;

use FOS\UserBundle\Mailer\Mailer as FOSMailer;
use FOS\UserBundle\Model\UserInterface;

class UserMailer extends FOSMailer
{
    public function sendResettingEmailMessage(UserInterface $user, $template = null, $route = null)
    {
        if($template === null) {
            $template = $this->parameters['resetting.template'];
        }

        if($route === null) {
            $route = 'enhavo_user_reset_password_confirm';
        }

        $url = $this->router->generate($route, array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], $user->getEmail());
    }

    public function sendRegisterConfirmMessage(UserInterface $user, $template = null, $route = null)
    {
        if($template === null) {
            $template = $this->parameters['confirmation.template'];
        }

        if($route === null) {
            $route = 'enhavo_user_registration_confirmed';
        }

        $url = $this->router->generate($route, array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($template, array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }
}
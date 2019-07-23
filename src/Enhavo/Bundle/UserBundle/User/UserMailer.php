<?php
/**
 * UserMailer.php
 *
 * @since 23/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\UserBundle\User;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use FOS\UserBundle\Mailer\Mailer as FOSMailer;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class UserMailer extends FOSMailer
{
    use TemplateTrait;
    use ContainerAwareTrait;

    public function sendResettingEmailMessage(UserInterface $user, $template = null, $route = null)
    {
        if($template === null) {
            $template = $this->parameters['resetting.template'];
        }

        if($route === null) {
            $route = 'enhavo_user_theme_reset_password_confirm';
        }

        $url = $this->router->generate($route, array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($this->getTemplate($template), array(
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
            $route = 'enhavo_user_theme_registration_confirm';
        }

        $url = $this->router->generate($route, array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render($this->getTemplate($template), array(
            'user' => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered, $this->parameters['from_email']['confirmation'], $user->getEmail());
    }
}

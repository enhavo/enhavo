<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 16.01.19
 * Time: 17:37
 */

namespace Enhavo\Bundle\UserBundle\Controller;


class UserConfiguration
{
    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $mailTemplate;

    /**
     * @var string
     */
    private $confirmRoute;

    /**
     * @var string
     */
    private $confirmedRoute;

    /**
     * @var string
     */
    private $redirectRoute;

    /**
     * @var string
     */
    private $form;

    /**
     * @param $default
     * @return string
     */
    public function getTemplate($default = null): ?string
    {
        if($this->template !== null) {
            return $this->template;
        }
        return $default;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @param $default
     * @return string
     */
    public function getMailTemplate($default = null): ?string
    {
        if($this->mailTemplate !== null) {
            return $this->mailTemplate;
        }
        return $default;
    }

    /**
     * @param string $mailTemplate
     */
    public function setMailTemplate(string $mailTemplate): void
    {
        $this->mailTemplate = $mailTemplate;
    }

    /**
     * @return string
     */
    public function getConfirmRoute($default = null): ?string
    {
        if($this->confirmRoute !== null) {
            return $this->confirmRoute;
        }
        return $default;
    }

    /**
     * @param string $confirmRoute
     */
    public function setConfirmRoute(string $confirmRoute): void
    {
        $this->confirmRoute = $confirmRoute;
    }

    /**
     * @return string
     */
    public function getRedirectRoute($default = null): ?string
    {
        if($this->redirectRoute !== null) {
            return $this->redirectRoute;
        }
        return $default;
    }

    /**
     * @param string $redirectRoute
     */
    public function setRedirectRoute(string $redirectRoute): void
    {
        $this->redirectRoute = $redirectRoute;
    }

    /**
     * @return string
     */
    public function getForm($default = null): ?string
    {
        if($this->form !== null) {
            return $this->form;
        }
        return $default;
    }

    /**
     * @param string $form
     */
    public function setForm(string $form): void
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getConfirmedRoute($default = null): ?string
    {
        if($this->confirmedRoute !== null) {
            return $this->confirmedRoute;
        }
        return $default;
    }

    /**
     * @param string $confirmedRoute
     */
    public function setConfirmedRoute(string $confirmedRoute): void
    {
        $this->confirmedRoute = $confirmedRoute;
    }
}
<?php

namespace Enhavo\Bundle\UserBundle\Event;

use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
    public const TYPE_CREATED = 'enhavo_user.user.created';
    public const TYPE_ACTIVATED = 'enhavo_user.user.activated';
    public const TYPE_REGISTRATION_CONFIRMED = 'enhavo_user.user.registration_confirmed';
    public const TYPE_PASSWORD_CHANGED = 'enhavo_user.user.password_changed';
    public const TYPE_EMAIl_CHANGED = 'enhavo_user.user.email_changed';
    public const TYPE_PASSWORD_RESET_REQUESTED = 'enhavo_user.user.password_reset_requested';
    public const TYPE_LOGIN_SUCCESS = 'enhavo_user.user.login_success';
    public const TYPE_LOGIN_FAILED = 'enhavo_user.user.login_failed';
    public const TYPE_PRE_AUTH = 'enhavo_user.user.pre_auth';

    private ?Response $response = null;
    private ?AuthenticationException $exception = null;

    public function __construct(
        protected string $type,
        protected UserInterface $user,
    )
    {
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }

    public function getException(): ?AuthenticationException
    {
        return $this->exception;
    }

    public function setException(?AuthenticationException $exception): void
    {
        $this->exception = $exception;
    }
}

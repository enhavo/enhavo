<?php

namespace Enhavo\Bundle\UserBundle\Configuration;

interface MailConfigurationInterface
{
    public function isMailEnabled(): bool;
    public function getMailSubject(): ?string;
    public function getMailSenderName(): ?string;
    public function getMailContext(): ?string;
    public function getMailTemplate(): ?string;
    public function getMailContentType(): ?string;
    public function getMailFrom(): ?string;
    public function getTranslationDomain(): ?string;
}

<?php

namespace Enhavo\Bundle\UserBundle\Configuration;

interface MailConfigurationInterface
{
    public function getMailSubject(): ?string;
    public function getMailName(): ?string;
    public function getMailContext(): ?string;
    public function getMailTemplate(): ?string;
    public function getMailContentType(): ?string;
    public function getMailFrom(): ?string;
    public function getTranslationDomain(): ?string;
}

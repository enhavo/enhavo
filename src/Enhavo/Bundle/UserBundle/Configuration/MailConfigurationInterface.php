<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

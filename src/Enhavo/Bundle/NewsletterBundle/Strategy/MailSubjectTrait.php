<?php
/**
 * MailSubjectTrait.php
 *
 * @since $date
 * @author $username-media
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy;


trait MailSubjectTrait
{
    protected function getAdminSubject(array $options)
    {
        return $this->trans($options['admin_subject'], [], $options['translation_domain']);
    }

    protected function getSubject(array $options)
    {
        return $this->trans($options['subject'], [], $options['translation_domain']);
    }
}

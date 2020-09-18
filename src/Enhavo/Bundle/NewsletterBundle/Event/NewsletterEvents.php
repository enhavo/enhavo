<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 01.12.16
 * Time: 14:33
 */

namespace Enhavo\Bundle\NewsletterBundle\Event;


class NewsletterEvents
{
    const EVENT_ADD_SUBSCRIBER = 'newsletter.add_subscriber';
    const EVENT_CREATE_SUBSCRIBER = 'newsletter.create_subscriber';
    const EVENT_CLEVERREACH_PRE_STORE = 'newsletter.cleverreach_pre_send';
    const EVENT_MAILCHIMP_PRE_STORE = 'newsletter.mailchimp_pre_send';
}

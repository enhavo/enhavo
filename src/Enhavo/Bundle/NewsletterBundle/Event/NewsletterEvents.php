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
    const EVENT_CREATE_SUBSCRIBER = 'newsletter.create_subscriber';
    const EVENT_PRE_ADD_SUBSCRIBER = 'newsletter.pre_add_subscriber';
    const EVENT_POST_ADD_SUBSCRIBER = 'newsletter.post_add_subscriber';
    const EVENT_PRE_ACTIVATE_SUBSCRIBER = 'newsletter.pre_activate_subscriber';
    const EVENT_POST_ACTIVATE_SUBSCRIBER = 'newsletter.post_activate_subscriber';
    const EVENT_CLEVERREACH_PRE_STORE = 'newsletter.cleverreach_pre_store';
    const EVENT_MAILCHIMP_PRE_STORE = 'newsletter.mailchimp_pre_store';
}

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
    const EVENT_ADD_SUBSCRIBER= 'newsletter.add_subscriber';
    const EVENT_PREVALIDATION = 'newsletter.pre_validation';
    const EVENT_CLEVERREACH_PRE_SEND = 'newsletter.cleverreach_pre_send';
}
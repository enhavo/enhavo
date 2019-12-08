<?php

namespace Enhavo\Bundle\DemoBundle\Fixtures\Fixtures;

use Enhavo\Bundle\DemoBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;

class NewsletterFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        /** @var Newsletter $newsletter */
        $newsletter = $this->container->get('enhavo_newsletter.factory.newsletter')->createNew();
        $newsletter->setTitle($args['title']);
        $newsletter->setSlug($args['slug']);
        $newsletter->setSubject($args['subject']);
        $newsletter->setGroup($this->createGroup($args['group']));
        $newsletter->setContent($this->createContent($args['content']));

        $this->translate($newsletter);
        return $newsletter;
    }

    function createGroup($args)
    {
        /** @var Group $group */
        $group = $this->container->get('enhavo_newsletter.factory.group')->createNew();
        $group->setName($args['name']);
        $group->setCode($args['code']);

        return $group;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Newsletter';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 20;
    }
}

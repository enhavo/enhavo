<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Fixtures\Fixtures;

use App\Fixtures\AbstractFixture;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;

class NewsletterFixture extends AbstractFixture
{
    public function create($args)
    {
        /** @var Newsletter $newsletter */
        $newsletter = $this->container->get('enhavo_newsletter.factory.newsletter')->createNew();
        $newsletter->setSlug($args['slug']);
        $newsletter->setSubject($args['subject']);
        $newsletter->addGroup($this->createGroup($args['group']));
        $newsletter->setContent($this->createContent($args['content']));

        $this->translate($newsletter);

        return $newsletter;
    }

    public function createGroup($args)
    {
        /** @var Group $group */
        $group = $this->container->get('enhavo_newsletter.factory.group')->createNew();
        $group->setName($args['name']);
        $group->setCode($args['code']);

        return $group;
    }

    public function getName()
    {
        return 'Newsletter';
    }

    public function getOrder()
    {
        return 20;
    }
}

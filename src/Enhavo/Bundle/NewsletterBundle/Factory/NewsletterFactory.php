<?php


namespace Enhavo\Bundle\NewsletterBundle\Factory;


use Enhavo\Bundle\BlockBundle\Factory\NodeFactory;
use Enhavo\Bundle\NewsletterBundle\Entity\Group;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;

class NewsletterFactory extends Factory
{
    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    public function __construct($className, NodeFactory $nodeFactory)
    {
        parent::__construct($className);

        $this->nodeFactory = $nodeFactory;
    }

    /**
     * @param Newsletter|null $originalResource
     * @return Newsletter
     * @throws \Exception
     */
    public function duplicate($originalResource)
    {
        if (!$originalResource) {
            return null;
        }

        /** @var Newsletter $newResource */
        $newResource = $this->createNew();

        $newContainer = $this->nodeFactory->duplicate($originalResource->getContent());
        $newResource->setContent($newContainer);

        $newResource->setSubject(sprintf('%s Copy', $originalResource->getSubject()));
        $newResource->setSlug($originalResource->getSlug());
        $newResource->setTemplate($originalResource->getTemplate());
        $newResource->setCreatedAt(new \DateTime());

        $groups = $originalResource->getGroups();

        /** @var Group $group */
        foreach ($groups as $group) {
            $newResource->addGroup($group);
        }

        return $newResource;
    }
}

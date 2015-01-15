<?php

namespace esperanto\ProjectBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use esperanto\ProjectBundle\Entity\Content;

class ContentData implements FixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $homepage = $this->addContent("homepage");
        $manager->persist($homepage);
        $concept = $this->addContent("concept");
        $manager->persist($concept);
        $aboutUs = $this->addContent("about_us");
        $manager->persist($aboutUs);
        $contact = $this->addContent("contact_details");
        $manager->persist($contact);
        $about = $this->addContent("about");
        $manager->persist($about);

        $manager->flush();
    }

    /**
     * @param $name
     * @param $type
     * @return Menu
     */
    public function addContent($page_title)
    {
        $content = new Content();
        $content->setTitle($page_title);

        return $content;
    }
}
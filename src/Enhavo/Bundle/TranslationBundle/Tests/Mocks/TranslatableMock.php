<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Mocks;



use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Sylius\Component\Resource\Model\ResourceInterface;

class TranslatableMock implements ResourceInterface, Slugable
{
    public $id;

    public function getId()
    {
        return $this->id;
    }

    /** @var string|null */
    private $slug;

    private $name;

    /** @var FileInterface */
    private $file;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return FileInterface
     */
    public function getFile(): FileInterface
    {
        return $this->file;
    }

    /**
     * @param FileInterface $file
     */
    public function setFile(FileInterface $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


}

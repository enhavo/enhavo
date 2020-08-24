<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Mocks;



use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class TranslatableMock implements ResourceInterface
{
    public $id;

    public function getId()
    {
        return $this->id;
    }

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


}

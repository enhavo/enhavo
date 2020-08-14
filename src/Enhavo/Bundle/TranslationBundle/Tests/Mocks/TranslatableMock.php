<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Mocks;



use Sylius\Component\Resource\Model\ResourceInterface;

class TranslatableMock implements ResourceInterface
{
    public $id;

    public function getId()
    {
        return $this->id;
    }

    private $name;

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
}

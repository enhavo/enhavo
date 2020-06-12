<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-11
 * Time: 22:46
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\EntityResolver;

use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\ChainResolver;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\Exception\ResolveException;
use PHPUnit\Framework\TestCase;

class ChainResolverTest extends TestCase
{
    public function testGetNameAndEntity()
    {
        $peter = new ChainResolverEntityDummy('Peter');
        $james = new ChainResolverEntityDummy('James');

        $resolver = new ChainResolver();

        $resolver->addResolver(new ClassResolverDummy('one', null), 20);
        $resolver->addResolver(new ClassResolverDummy('two', null), 10);
        $this->assertEquals('one', $resolver->getName($peter));

        $resolver->addResolver(new ClassResolverDummy('three', $james), 100);
        $this->assertEquals('three', $resolver->getName($james));

        $resolver->addResolver(new ClassResolverDummy(null, null), 200);
        $this->assertEquals('three', $resolver->getName($peter));

        $this->assertEquals('James', $resolver->getEntity(1, 'test')->name);
    }

    public function testGetNameNotFound()
    {
        $this->expectException(ResolveException::class);
        $resolver = new ChainResolver();
        $resolver->getName(new ChainResolverEntityDummy('Peter'));
    }

    public function testGetEntityNotFound()
    {
        $resolver = new ChainResolver();
        $this->assertNull($resolver->getEntity(1, ChainResolverEntityDummy::class));
    }
}

class ChainResolverEntityDummy
{
    public $name;

    /**
     * EntityDummy constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}

class ClassResolverDummy implements EntityResolverInterface
{
    private $name;
    private $entity;

    public function __construct($name, $entity)
    {
        $this->name = $name;
        $this->entity = $entity;
    }

    public function getName(object $entity): string
    {
        if($this->name === null) {
            throw new ResolveException();
        }
        return $this->name;
    }

    public function getEntity(int $id, string $name): ?object
    {
        return $this->entity;
    }
}

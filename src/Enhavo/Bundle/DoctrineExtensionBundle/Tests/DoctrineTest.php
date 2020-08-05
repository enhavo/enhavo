<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-03
 * Time: 16:41
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

abstract class DoctrineTest extends TestCase
{
    /** @var EntityManager */
    protected $em;

    /** @var string */
    protected $entityDir;

    /** @var string */
    protected $proxyDir;

    protected function setUp()
    {
        $this->bootstrap();
        $this->updateSchema();
    }

    protected function tearDown()
    {
        $this->em->close();
        unset($this->em);
    }

    protected function bootstrap()
    {
        if($this->proxyDir) {
            $finder = new Finder();
            $finder->files()->in($this->proxyDir);
            foreach ($finder as $file) {
                if($file->getExtension() === 'php') {
                    unlink($file->getRealPath());
                }
            }
        }

        $config = Setup::createAnnotationMetadataConfiguration(array($this->entityDir), true, $this->proxyDir ?? $this->proxyDir);
        $conn = ['url' => 'sqlite:///:memory:'];
        $this->em = EntityManager::create($conn, $config);
    }

    protected function updateSchema()
    {
        $schema = new SchemaTool($this->em);
        $metadata = [];
        $classNames = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach($classNames as $class) {
            $metadata[] = $this->em->getClassMetadata($class);
        }
        $schema->createSchema($metadata);
    }
}

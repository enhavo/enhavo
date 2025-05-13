<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
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

    protected function setUp(): void
    {
        $this->bootstrap();
        $this->updateSchema();
    }

    protected function tearDown(): void
    {
        $this->em->close();
        unset($this->em);
    }

    protected function bootstrap()
    {
        if ($this->proxyDir) {
            $finder = new Finder();
            $finder->files()->in($this->proxyDir);
            foreach ($finder as $file) {
                if ('php' === $file->getExtension()) {
                    unlink($file->getRealPath());
                }
            }
        }

        $config = ORMSetup::createAttributeMetadataConfiguration([$this->entityDir], true, $this->proxyDir ?? $this->proxyDir);
        $conn = ['url' => 'sqlite:///:memory:'];
        $this->em = EntityManager::create($conn, $config);
    }

    protected function updateSchema()
    {
        $schema = new SchemaTool($this->em);
        $metadata = [];
        $classNames = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        foreach ($classNames as $class) {
            $metadata[] = $this->em->getClassMetadata($class);
        }
        $schema->createSchema($metadata);
    }
}

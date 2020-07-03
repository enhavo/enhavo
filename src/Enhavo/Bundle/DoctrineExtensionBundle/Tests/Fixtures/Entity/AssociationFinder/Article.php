<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-03
 * Time: 16:34
 */

namespace Enhavo\Bundle\DoctrineExtensionBundle\Tests\Fixtures\Entity\AssociationFinder;

/**
 * @Entity
 */
class Article
{
    /**
     * @var integer|null
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @var File|null
     * @OneToOne(targetEntity="File")
     */
    private $file;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 26.08.17
 * Time: 17:42
 */

namespace Enhavo\Bundle\MediaBundle\Entity;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Model\FormatInterface;

/**
 * Format
 */
class Format implements FormatInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var FileInterface
     */
    private $file;

    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var \DateTime|null
     */
    private $filterOperationsLock;


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @inheritdoc
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return;
    }

    /**
     * @inheritdoc
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @inheritdoc
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return;
    }

    /**
     * @inheritdoc
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @inheritdoc
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return;
    }

    /**
     * @inheritdoc
     */
    public function getFilename()
    {
        $filename = $this->getFile()->getFilename();
        $pathInfo = pathinfo($filename);
        if(isset($pathInfo['extension'])) {
            return sprintf('%s.%s', $pathInfo['filename'], $this->getExtension());
        }
        return $pathInfo['filename'];
    }

    /**
     * @inheritdoc
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @inheritdoc
     */
    public function setFile(FileInterface $file)
    {
        $this->file = $file;
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritdoc
     */
    public function setContent(ContentInterface $content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime|null
     */
    public function getFilterOperationsLock()
    {
        return $this->filterOperationsLock;
    }

    /**
     * @param \DateTime|null $filterOperationsLock
     */
    public function setFilterOperationsLock($filterOperationsLock)
    {
        $this->filterOperationsLock = $filterOperationsLock;
    }
}
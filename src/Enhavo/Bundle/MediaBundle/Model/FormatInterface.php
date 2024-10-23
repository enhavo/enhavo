<?php

/**
 * FileInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Model;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;

interface FormatInterface extends FileContentInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return FileInterface
     */
    public function setMimeType($mimeType);

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType();

    /**
     * Set extension
     *
     * @param string $extension
     * @return FileInterface
     */
    public function setExtension($extension);

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $filename
     *
     * @return FileInterface
     */
    public function setName($filename);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param array $parameters
     * @return FileInterface
     */
    public function setParameters($parameters);

    /**
     * @return ContentInterface
     */
    public function getContent();

    /**
     * @param ContentInterface $content
     */
    public function setContent(ContentInterface $content);

    /**
     * @param FileInterface $file
     */
    public function setFile(FileInterface $file);

    /**
     * @return FileInterface
     */
    public function getFile();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @return \DateTime|null
     */
    public function getLockAt();

    /**
     * @param \DateTime|null $lockAt
     */
    public function setLockAt($lockAt);
}

<?php

/**
 * FileInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Model;

use Enhavo\Bundle\MediaBundle\Content\ContentInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface FileInterface extends ResourceInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param $token string
     */
    public function setToken($token);

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
     * Set order
     *
     * @param integer $order
     * @return FileInterface
     */
    public function setOrder($order);

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param string $filename
     *
     * @return FileInterface
     */
    public function setFilename($filename);

    /**
     * Sets the parameter $key to value $value
     *
     * @param string $key
     * @param string $value
     * @return FileInterface
     */
    public function setParameter($key, $value);

    /**
     * Returns the parameter value for $key.
     * If no value is set for $key, returns null.
     *
     * @param string $key
     * @return string|null
     */
    public function getParameter($key);

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
     * @return boolean
     */
    public function isGarbage();

    /**
     * @param boolean $garbage
     * @param \DateTime $garbageTimestamp
     *
     * @return FileInterface
     */
    public function setGarbage($garbage, \DateTime $garbageTimestamp = null);

    /**
     * @return \DateTime
     */
    public function getGarbageTimestamp();

    /**
     * @param \DateTime $garbageTimestamp
     *
     * @return FileInterface
     */
    public function setGarbageTimestamp($garbageTimestamp);

    /**
     * Returns if this file is an image based on the mime type
     *
     * @return bool
     */
    public function isImage();

    /**
     * @return ContentInterface
     */
    public function getContent();

    /**
     * @param ContentInterface $content
     */
    public function setContent(ContentInterface $content);

    /**
     * @return string
     */
    public function getMd5Checksum();

    /**
     * @param string $md5Checksum
     */
    public function setMd5Checksum($md5Checksum);

    /**
     * @return bool
     */
    public function isLibrary();

    /**
     * @param bool $library
     */
    public function setLibrary($library);

    /**
     * @param \DateTime|null $garbageCheckedAt
     */
    public function setGarbageCheckedAt(?\DateTime $garbageCheckedAt);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();
}

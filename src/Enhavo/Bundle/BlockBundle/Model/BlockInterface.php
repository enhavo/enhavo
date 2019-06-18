<?php

/**
 * BlockInterface.php
 *
 * @since 22/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Model;

interface BlockInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @param ContainerInterface $container
     * @return mixed
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @return ContainerInterface
     */
    public function getContainer();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return BlockTypeInterface
     */
    public function getBlockType();

    /**
     * @param BlockTypeInterface $block
     */
    public function setBlockType(BlockTypeInterface $block = null);

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param int $position
     */
    public function setPosition($position);
}
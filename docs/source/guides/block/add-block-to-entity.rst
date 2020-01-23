Add block to entity
===================

If you want to use a block grid inside a entity you have to add the
the ``Enhavo\Bundle\BlockBundle\Model\NodeInterface`` to target entity.

First update your meta file of your entity.

.. code-block:: yaml

    manyToOne:
        content:
            cascade: ['persist', 'refresh', 'remove']
            targetEntity: Enhavo\Bundle\BlockBundle\Model\NodeInterface

And add a getter and setter function to the entity class.
Set the property and type of the Node correctly. In this case the type is root, because
it is the entry point of the block node tree. Property should be the member name where
the Node is stored. This is important to browse throught the node three later.

.. code-block:: php

    Enhavo\Bundle\BlockBundle\Model\NodeInterface

    class MyEntity
    {
        /**
         * @param NodeInterface|null
         */
        private $content;

        public function getContent(): ?NodeInterface
        {
            return $this->content;
        }

        public function setContent(?NodeInterface $content)
        {
            if($content) {
                $content->setType(NodeInterface::TYPE_ROOT);
                $content->setProperty('content');
            }
            return $this;
        }
    }


And add a migration file and execute it to update your database schema.
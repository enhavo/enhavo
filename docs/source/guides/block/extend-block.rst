Extend Block
============

Sometimes a block don't have all the properties you need. So you want to extend a enhavo block. This is of course possible.
You just need to create a entity block class with the doctrine meta data and create a listener that will register the extension meta
data to doctrine. At the end you need to configure the current block configuration to use your extended class.

Create custom block
-------------------

Create your custom Block class. If you use yaml or xml for your doctrine meta data you need to create a separate meta file.
If you use annotations just add doctrine annotations to your entity class.

.. code:: php

    namespace App\Entity\Block;

    class TextBlock extends Enhavo\Bundle\BlockBundle\Model\TextBlock
    {
        // add your custom properties and functions
    }


Add extend listener
-------------------

Add a doctrine extend listener as a service to the dependency injection container.
You have to define the class you want to extend from and the class which extends.
This step is needed because you can't add the meta data for extension to the enhavo TextBlock because
you can't change the vendor files. This Listener will hook into doctrine and set all configuration to make
a single table inheritance.

.. code:: yaml

    # config/services.yaml

    app.block.text.extend_listener:
        class: Enhavo\Bundle\AppBundle\EventListener\DoctrineExtendListener
        arguments:
            - Enhavo\Bundle\BlockBundle\Model\Block\TextBlock
            - App\Entity\Block\TextBlock
            - true
        tags:
            - { name: doctrine.event_subscriber, connection: default }

Change block configuration
--------------------------

Change the block configuration and use your custom model class.

.. code:: yaml

    # config/packages/enhavo.yaml

    enhavo_block:
        blocks:
            text:
                type: text
                model: App\Entity\Block\TextBlock

Make Block
===========

Create block without properties
************
.. code-block:: bash

  bin/console make:enhavo:block

Use YAML configuration file
************
.. code-block:: bash

  bin/console make:enhavo:block -p config/blocks/MyBlock.yaml

Block YAML configuration example:
:doc:`../../book/block-bundle/make-block-configuration`

Templates can be used and also generated to shorten configuration of frequently used properties.
E.g. all of your blocks do contain jump marks then it might be useful to have a JumpMark template.
Create a file ``config/block/templates/JumpMark.yaml``

.. code-block:: yaml

  type: string
  nullable: true
  form:
      use:
          - Symfony\Component\Form\Extension\Core\Type\TextType
      class: TextType
      options:
          label: "'Jump Mark'"



Templates can then be used by simply adding them to the property config:

.. code-block:: yaml

  MyBlock:
    properties:
      jumpMark:
        template: JumpMark

Templates already included
************

BlockNodeType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/BlockNodeType.yaml
  :language: yaml
BooleanType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/BooleanType.yaml
  :language: yaml
ChoiceType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/ChoiceType.yaml
  :language: yaml
ListType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/ListType.yaml
  :language: yaml
MediaType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/MediaType.yaml
  :language: yaml
MediaTypeMultiple
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/MediaTypeMultiple.yaml
  :language: yaml
PositionType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/PositionType.yaml
  :language: yaml
TextareaType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/TextareaType.yaml
  :language: yaml
TextType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/TextType.yaml
  :language: yaml
WysiwygType
""""""""""""
.. literalinclude:: ../../../../src/Enhavo/Bundle/BlockBundle/Resources/block/templates/WysiwygType.yaml
  :language: yaml

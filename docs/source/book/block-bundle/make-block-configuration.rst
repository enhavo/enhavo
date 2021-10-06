Make Block Configruation
============

.. code-block:: yaml

  Block/MyBlock: # name of the entity including subdirectory for all gereated files/classes. the subdirectory is optional.
      namespace: App # add Block files to a certain Bundle [optional]
      groups: # groups to use in block-config/type [optional]
          - layout
          - content
      label: 'Logowall' # the label to use in block-config/type [optional]
      block_type: true # generate block type [optional]
      use: # add use statements to Block entity
          - Doctrine\Common\Collections\Collection
          - Doctrine\Common\Collections\ArrayCollection
      form:
          use: # add use statements to Block FormType
              - Symfony\Component\Form\Extension\Core\Type\TextType

      properties: # a list of properties to be added to block/orm/form-type
          overline:
              type: string # the php data-type
              orm_type: string # can be used to have another type in ORM
              nullable: true # configure the property nullable in Block entity and ORM [optional]
              form:
                  class: TextType # form type to be used
                  options:
                      label: "'Overline'"
          layout:
              template: ChoiceType # use/create templates for complex types that are used multiple times
              form:
                  options:
                      choices:
                          'Linksbündig': "'left'" # for ChoiceType choices you need to escape the keys also!
                          'Rechtsbündig': "'right'" # double AND single quotes to add string values!
                      expanded: 'true' # single OR double quotes to add boolean values!
          text:
              template: TextareaType

          children:
              type: ListType
              type_options:
                  entry_class: MyBlockItem # means that adder/remover is generated
              relation: # defines the doctrine relation
                  target_entity: App\Entity\Block\MyBlockItem # target entity for the relation (entry_class but including namespace)
                  mapped_by: myBlock # orm relation and also creates setMyBlock($this/null) in adder/remover
              form: # settings for the BlockFormType
                  options: # form type options
                      entry_type: MyBlockItemType::class # no quotes to place value directly
                      label: "'Children'"

      classes: # you can create multiple related or non related entities here. config rules as read above.
          Block/MyBlockItem:
              properties:
                  myBlock:
                      type: MyBlock
                      nullable: true
                      relation:
                          type: manyToOne
                          target_entity: App\Entity\Block\MyBlock
                          inversed_by: children
                  position:
                      template: PositionType
                  title:
                      template: TextType
                  picture:
                      template: MediaType
                      form:
                          options:
                              label: "'Bild'"
                  content:
                      template: BlockNodeType # Blocks that also contain other Blocks
                      form:
                          options:
                              label: "'Logowall-Item-Content'"
                              item_groups: "['logowall']" # groups to be loaded into BlockNodeType
                  files:
                      template: MediaTypeMultiple # multiple files

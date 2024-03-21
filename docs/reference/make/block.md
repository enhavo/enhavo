## Block

Create block without properties

```bash
$ bin/console make:enhavo:block
```

Use YAML configuration file 

```bash
$ bin/console make:enhavo:block -p config/blocks/MyBlock.yaml
```

Block YAML configuration example:
`../../book/block-bundle/make-block-configuration`

Templates can be used and also generated to shorten configuration of
frequently used properties. E.g. all of your blocks do contain jump
marks then it might be useful to have a JumpMark template. Create a file
`config/block/templates/JumpMark.yaml`

```yaml
type: string
nullable: true
form:
    use:
        - Symfony\Component\Form\Extension\Core\Type\TextType
    class: TextType
    options:
        label: "'Jump Mark'"
```

Templates can then be used by simply adding them to the property config:

```yaml
MyBlock:
  properties:
    jumpMark:
      template: JumpMark
```

### Templates already included

-   BlockNodeType
-   BooleanType
-   ChoiceType
-   ListType
-   MediaType
-   MediaTypeMultiple
-   PositionType
-   TextareaType
-   TextType
-   WysiwygType
- 

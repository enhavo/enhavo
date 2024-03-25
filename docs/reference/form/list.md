## List

The `enhavo_list` helps you to list individual fields on your page. To
use the `enhavo_list` just follow these steps:

1)  Add to FormType
2)  Add to your orm.yml
3)  Add properties

### Add to FormType

Add the `enhavo_list` to your FormType. For the parameter `type` you can
either add a FormType from an Entity you have created or use a doctrine
type. You can only add more list items if the `allow_add` parameter is
true. If you want to delete added items, set the `allow_delete`
parameter to true.

In this example we use a doctrine formType.

```php
$builder->add('tag', 'enhavo_list', array(
    'type' =>  'text',
    'label' => 'label.tag',
    'prototype' => true,
    'allow_add' => true,
    'by_reference' => false,
    'allow_delete' => true
));
```

If you want to use your own entity, just replace the `type` like this:

```php
'type' =>  'project_tag',
```

In this example we added a tag to the `enhavo_list`.

### Add to your orm.yml

Add the list type to your `arme.orm.yml` . If you added a doctrine type,
just add a field with type `array` like this:

```yaml
tag:
    type: array
    nullable: true
```

If you added your own formType, create a `oneToMany` relationship. In
this case you also have to add some extra code to the add function in
your entity:

```php
$tag->setAcme($this);
```

and to the remove function:

```php
$this->tags->removeElement($tag);
```

### Add properties

There are two more properties you can add to the formType. Add these
like you have done it with the `allow_delete` property. If you want to
separate the items from eachother with a border, set the `border`
property to true. If not you can just skip this step.

```php
'border' => false,
```

If you want to sort the added items, set the `sortable` property to
true.

```php
'sortable' => true,
```

If you have used your own entity, you have to do a few steps more. First
you add `sortable_property` like you have done it with the border and
sortable property before.

```php
'sortable_property' => 'order'
```

Then you add a `order` field to the orm.yml of the entity you have used
in the `enhavo_list`.

```yaml
order:
    column: '`order`'
    type: string
    length: 255
```

After that use the order as a hidden field in the tagType like this:

```php
$builder->add('order', 'hidden', array(
          'attr' => array('class' => 'order')
      ));
```

The name of the class has to be the same you used for the
`sortable_property`.

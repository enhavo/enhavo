## Collection

Create a new collection or array and duplicate all items from old collection into the new collection.
Use this type if you want to make a deep copy of a collection.

<ReferenceTable 
    type="collection"
    className="Enhavo\Bundle\ResourceBundle\Duplicate\Type\CollectionDuplicateType"
    parent="Enhavo\Bundle\ResourceBundle\Duplicate\BaseActionType"
>
<template v-slot:options>
    <ReferenceOption name="map_target" :required="false" />, 
    <ReferenceOption name="by_reference" :required="false" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="groups" type="groups" />
</template>
</ReferenceTable>

### by_reference

**type**: `bool` **default:** `false`

When adding an item to the new collection, the add function of the parent will be used instead of the add function of the collection.

```php
// by_reference is false
$newCollection->add($newItem);
$parent->setCollection($newCollection)

// by_reference is true
$parent->addItem($newItem)
```

### map_target

**type**: `bool` **default:** `false`

If you duplicate to a target collection and `map_target` is true, then it will also call a duplicate to the target at
the same index position.

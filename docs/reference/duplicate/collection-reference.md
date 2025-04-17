## Collection reference

Copy the reference of a collection. If the duplicate has a target, then the items of the original collection will
be added or deleted to transform into the source collection.

<ReferenceTable 
    type="collection_reference"
    className="Enhavo\Bundle\ResourceBundle\Duplicate\Type\CollectionReferenceDuplicateType"
    parent="Enhavo\Bundle\ResourceBundle\Duplicate\BaseActionType"
>
<template v-slot:inherit>
    <ReferenceOption name="groups" type="groups" />
</template>
</ReferenceTable>

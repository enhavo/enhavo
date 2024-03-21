## HierarchyNotCircular

<ReferenceTable
className="Enhavo\Bundle\FormBundle\Validator\Constraints\HierarchyNotCircular"
>
<template v-slot:options>
    <ReferenceOption name="parentProperty" :required="true" />,
    <ReferenceOption name="message" />
</template>
</ReferenceTable>

### parentProperty

**type**: `string`

The parent property

```yaml
Enhavo\Bundle\PageBundle\Entity\Page:
    constraints:
        - Enhavo\Bundle\FormBundle\Validator\Constraints\HierarchyNotCircular:
              parentProperty: parent
              message: 'Hierarchy circle detected'
```

### message

**type**: `string`

Define message if violation was triggered.

## Action

This column type allows the user to integrate any
`action` including all its
functionality in this column.

<ReferenceTable
type="action"
className="Enhavo\Bundle\AppBundle\Column\Type\ActionType"
>
<template v-slot:opions>
    <ReferenceOption name="action" type="action" :required="true"/>
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="condition" />,
    <ReferenceOption name="width" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="component" />
</template>
</ReferenceTable>


### action {#action_action}

**type**: string

The action to be placed in the column. Take a look to all possible
actions `here <action>`{.interpreted-text role="ref"}.

```yaml
columns:
    myColumn:
        type: action
        action:
            type: myAction
            route: my_action_route
            # ... further action options
        # ... further column options
```


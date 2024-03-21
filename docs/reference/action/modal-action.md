## Modal

<ReferenceTable
type="modal"
className="Enhavo\Bundle\AppBundle\Action\Type\ModalActionType"
>
> 
<template v-slot:options>
    <ReferenceOption name="modal" type="modal" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="icon" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" />
</template>
</ReferenceTable>


### modal {#modal_modal}

**type**: `string`

``` yaml
actions:
    modal:
        type: modal
        modal: myModal
```

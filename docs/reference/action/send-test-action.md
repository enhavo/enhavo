## Newsletter Send Test

Sends the selected newsletter to any e-mail address for testing. The
address can be changed each time. In contrast to the normal
`newsletter_send_action`, the newsletter is sent immediately.


<ReferenceTable
type="newsletter_send"
className="Enhavo\Bundle\NewsletterBundle\Action\SendTestActionType"
>
<template v-slot:options>
    <ReferenceOption name="modal" type="send_newsletter_test" />,
</template>
<template v-slot:inherit>
    <ReferenceOption name="label" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" />
</template>
</ReferenceTable>

### modal {#modal_send_newsletter_test}

**type**: `string`

``` php
[
    'component' => 'ajax-form-modal',
    'route' => 'enhavo_newsletter_newsletter_test_form',
    'actionRoute' => 'enhavo_newsletter_newsletter_test'
]
```

In this modal the e-mail address is entered and the routes for the form
and the controller are defined.

``` yaml
actions:
    modal:
        type: modal
        modal:
            component: my_modal_component
            route: my_modal_form_route
            actionRoute: my_modal_action_route
```

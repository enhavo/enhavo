## Event

Executes all handlers and behaviors of the specified jQuery event.

<ReferenceTable
type="event"
className="Enhavo\Bundle\AppBundle\Action\Type\EventActionType"
>
<template v-slot:options>
    <ReferenceOption name="route" type="duplicate" :required="true" />,
    <ReferenceOption name="label" :required="true" />,
    <ReferenceOption name="icon" :required="true" />
</template>
<template v-slot:inherit>
    <ReferenceOption name="route_parameters" />,
    <ReferenceOption name="translation_domain" />,
    <ReferenceOption name="hidden" />,
    <ReferenceOption name="permission" />,
    <ReferenceOption name="view_key" />,
    <ReferenceOption name="confirm" />,
    <ReferenceOption name="confirm_message" />,
    <ReferenceOption name="confirm_label_ok" />,
    <ReferenceOption name="confirm_label_cancel" />
</template>
</ReferenceTable>

### event {#event_duplicate}

**type**: `string`

The jQuery event that is manually triggered when the action is clicked.

``` yaml
actions:
    event:
        type: event
        event: myEvent
```

The event-string \"myEvent\" represents the jQuery event which has to be
present in your project like this general example:

``` javascript
$(document).on("myEvent", function() {
    doSomething();
});
```

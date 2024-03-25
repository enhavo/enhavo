## Listener

The vue forms have also their own listener sub system. To listen and
fire custom events and to build a more consistent event system. To add a
listener on form we recommend to use visitors. Inside a visitor you are
able to add the current form (or any other) to the listener.

```js
import {AbstractFormVisitor} from "@enhavo/vue-form/form/FormVisitor"
import {FormEventDispatcher} from "@enhavo/vue-form/form/FormEventDispatcher"

export class MyCustomVisitor extends AbstractFormVisitor
{
    constructor(
        private formEventDispatcher: FormEventDispatcher,
    ) {
        super();
    }

    supports(form: Form): boolean
    {
        return form.componentVisitors.indexOf('my_custom') >= 0;
    }

    apply(form: Form): Form | void
    {
        this.formEventDispatcher.on('change', (event: ChangeEvent) => {
            // Do something if this form has changed, e.g. hide a child if value is changed.
            // Don't use the form var from outside this scope, always use the event and retrieve the form from it,
            // because the outside form may not be reactive and thus changes might have no effects!
            if (event.form.getValue() === 'something') {
              event.form.parent.get('myChild').visible = false;
            }
        }, form.get('field'));
    }
}
```

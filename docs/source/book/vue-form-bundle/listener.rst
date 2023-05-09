Listener
========

The vue forms have also their own listener sub system. To listen and fire custom events and to build a more consistent event system.
To add a listener on form we recommend to use visitors. Inside a visitor you are able to add the current form (or any other) to
the listener.

.. code-block:: js

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
              // do something if this form has changed
              // e.g. hide a child if value is changed
              if (form.getValue() === 'something') {
                form.get('myChild').visible = false;
              }
          }, form);
      }
  }

Further Reading
---------------

-  :doc:`/book/vue-form-bundle/visitors`
-  :doc:`/guides/vue-form/how-to-customize-form`

Visitors
========

Before vue is loading the form data, you are able to make changes to the form data. To do so, you can add a ``FormVisitor``
object. A visitor implements ``FormVisitorInterface``, that require a support and apply function.
In the support function you have to return a boolean if the apply function should be used for this form child.
Inside the apply function you can make changes on the child e.g. add an attribute or change the component.

You can add the visitor to the ``FormFactory``, so every time the form will be created, the visitor is applied to the form data.

.. code-block:: typescript

  import {createApp, reactive} from "vue";
  import {FormFactory} from "@enhavo/vue-form/form/FormFactory";
  import {FormVisitor} from "@enhavo/vue-form/form/FormVisitor";
  import {Form} from "@enhavo/vue-form/model/Form";

  let formFactory = new FormFactory();

  formFactory.addVisitor(new FormVisitor(
      (form: Form) => {
          return form.component == 'form-choice' && !form.expanded;
      },
      (form: Form) => {
          form.attr.class = 'my-class'
      },
  );

  let data = {}; // get data somewhere
  let form = formFactory.create(data.form);

Apply visitor to FormType
-------------------------

Instead of checking the component inside the ``supports`` function. You can also check the ``componentVisitors`` property
and apply the visitor to a predefined string.

.. code-block:: typescript

  let formFactory = new FormFactory();

  formFactory.addVisitor(new FormVisitor(
      (form: Form) => {
          return form.componentVisitors.indexOf('custom_visitor') >= 0; // you can also check for a name, component or value
      },
      (form: Form) => {
          // do something
      },
  );

In the form builder definition you are able to apply that visitor to your form.

.. code-block:: php

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
          $builder->add('name', TextType::class, [
              'component_visitors' => ['custom_visitor'],
          ]);
    }

Further Reading
---------------

-  :doc:`/guides/vue-form/how-to-customize-form`
-  :doc:`/book/vue-form-bundle/listener`

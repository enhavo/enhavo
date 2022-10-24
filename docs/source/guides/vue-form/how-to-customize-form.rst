How to customize form
=====================

Before vue is loading the form data, you are able to make changes in the form data. To do so, you create a ``Theme``
object and add ``ThemeVisitor``'s. A visitor implements ``ThemeVisitorInterface``, that require a support and apply function.
In the support function you have to return a boolean if the apply function should be used for this form child.
Inside the apply function you can make changes on the child e.g. add an attribute or change the component.

If you just want to change the component you may use the  ``ThemeComponentVisitor``

.. code-block:: typescript


  import {Theme, ThemeVisitor, ThemeComponentVisitor} from "@enhavo/vue-form/form/Theme";
  import {FormData} from "@enhavo/vue-form/data/FormData";

  let theme = new Theme;

  // add visitor with custom callbacks
  theme.addVisitor(new ThemeVisitor(
      (form: FormData) => {
          return form.component == 'form-choice' && !form.expanded;
      },
      (form: FormData) => {
          form.attr.class = 'custom-select'
      },
  );


  // change the component
  theme.addVisitor(new ThemeComponentVisitor('form-simple', 'form-custom-simple');

  let form = Form.create(form);
  form.addTheme(theme);

How to customize form
=====================


Use Visitors
------------

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


Use Slots
---------

If you want to change the html, you can use `vue slots <https://vuejs.org/guide/components/slots.html>`_ to overwrite
parts of a component. The component must provide slots. Check the reference to check which are available.

First you use ``ThemeComponentVisitor`` to replace the origin component. Then import this component and pass the
form property.

.. code-block:: vue

  <template>
      <form-list :form="form">
          <template v-slot:item-down-button>Custom HTML</template>
      </form-list>
  </template>

  <script lang="ts">
  import {Vue, Options, Inject, Prop} from "vue-property-decorator";
  import FormListComponent from "@enhavo/form/components/FormListComponent.vue";

  @Options({
      components: {'form-list': FormListComponent},
  })
  export default class extends Vue
  {
      @Prop()
      public form: FormData
  }
  </script>


Further Reading
---------------

- :doc:`Vue Form Reference </reference/vue-form/index>`

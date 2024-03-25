## How to customize form

### Use Visitors

Make sure you are familiar with the [Vue Form Visitors
\</book/vue-form-bundle/visitors\>]{.title-ref}. Use the `Theme` to add
multiple `FormVisitor` together, and apply them to a form. You can add
the theme object to the `FormFactory` to use it for all forms that will
be created or just to single one on the `create` function.

```typescript
import {Theme} from "@enhavo/vue-form/form/Theme";
import {FormComponentVisitor} from "@enhavo/vue-form/form/FormVisitor";
import {FormFactory} from "@enhavo/vue-form/form/FormFactory";

let formFactory = new FormFactory();

let theme = new Theme;

// change the component
theme.addVisitor(new FormComponentVisitor('form-simple', 'form-custom-simple');

// add to all forms ...
formFactory.addTheme(theme);

// ... or as second parameter to a single form
let form = formFactory.create(form, theme);
```

### Use Slots

If you want to change the html, you can use [vue
slots](https://vuejs.org/guide/components/slots.html) to overwrite parts
of a component. The component must provide slots. Check the reference to
check which are available.

First you use `ThemeComponentVisitor` to replace the origin component.
Then import this component and pass the form property.

```vue
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
```

### Further Reading

* [Vue Form Reference](/reference/vue-form/index.md)
* [Vue Form Visitors](/book/vue-form-bundle/index.md)

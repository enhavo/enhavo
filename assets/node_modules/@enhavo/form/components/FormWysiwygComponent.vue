<template>
    <input type="hidden"
           :name="form.fullName"
           :required="form.required"
           :disabled="form.disabled"
           v-model="form.value"
           ref="element"
           @change="form.dispatchChange()"
    />
    <div class="wysiwyg-container">
        <div :id="form.editorId" ref="editorElement" v-once></div>
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop, Inject} from "vue-property-decorator";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

@Options({})
export default class extends Vue
{
    @Prop()
    form: WysiwygForm;

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        this.form.editorElement = <HTMLElement>this.$refs.editorElement;
        FormUtil.updateAttributes(this.form.element, this.form.attr);

        if (this.form.editorElement.id !== this.form.editorId) {
            this.form.initWysiwyg();
        }
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        this.form.editorElement = <HTMLElement>this.$refs.editorElement;
        FormUtil.updateAttributes(this.form.element, this.form.attr);
        this.form.initWysiwyg();
    }
}

</script>

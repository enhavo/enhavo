<template>
    <div class="wysiwyg-container">
        <editor
            :id="form.editorId"
            :placeholder="form.placeholder"
            :name="form.fullName"
            :required="form.required"
            :disabled="form.disabled"
            :title="form.attr.title"
            v-model="form.value"
            ref="element"
            :init="form.settings"
            :key="form.editorKey"
        />
    </div>
</template>

<script lang="ts">
import {Vue, Options, Prop, Inject} from "vue-property-decorator";
import {Util} from "@enhavo/vue-form/form/Util";
import {FormWysiwyg} from "@enhavo/form/form/model/FormWysiwyg";
import Editor from '@tinymce/tinymce-vue'

@Options({
    components: {
        'editor': Editor
    }
})
export default class extends Vue
{
    @Prop()
    form: FormWysiwyg;

    created()
    {
        this.form.initCallback = () => {
            this.$forceUpdate();
        };
    }

    updated()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        Util.updateAttributes(this.form.element, this.form.attr);
    }

    mounted()
    {
        this.form.element = <HTMLElement>this.$refs.element;
        Util.updateAttributes(this.form.element, this.form.attr);
    }
}

</script>

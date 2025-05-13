<template>
    <div :key="form.id" v-show="form.isVisible()" class="form-wysiwyg" >
        <form-wysiwyg-modal-container :form="form"></form-wysiwyg-modal-container>

        <input type="hidden"
               :name="form.fullName"
               :required="form.required"
               :disabled="form.disabled"
               v-model="form.value"
               :ref="(el) => form.setElement(el as HTMLElement)"
               @change="form.dispatchChange()"
        />

        <div v-if="form.editor" class="wysiwyg-menu">
            <template v-for="row in form.getConfig().menu">
                <form-wysiwyg-menu-group :configuration="row" :form="form"></form-wysiwyg-menu-group>
            </template>
        </div>

        <form-wysiwyg-search-replace-inline-form :form="form" v-if="form.searchAndReplaceOpen"></form-wysiwyg-search-replace-inline-form>
        <form-wysiwyg-table-inline-form :form="form" v-if="form.editor && form.editor.isActive('table')"></form-wysiwyg-table-inline-form>

        <div class="wysiwyg-editor-container" :class="'wysiwyg-editor-' + form.getConfig().name" @click="form.editor.view.dom.focus();">
            <editor-content :editor="form.editor" class="wysiwyg-editor" :class="form.additionalCssClasses" ref="editorElement" />
        </div>
        <div class="wysiwyg-editor-bottom-line"></div>
    </div>
</template>

<script setup lang="ts">
import {ref, onMounted, onBeforeUnmount} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {EditorContent} from '@tiptap/vue-3';

const props = defineProps<{
    form: WysiwygForm
}>()

const editorElement = ref(null);

onMounted(() => {
    props.form.initEditor();
});

onBeforeUnmount(() => {
    props.form.destroyEditor();
});

</script>

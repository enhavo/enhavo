<template>
    <div class="wysiwyg-inline-menu-form wysiwyg-search-replace-form">
        <div class="form-row wysiwyg-inline-menu-form-row">
            <label>Search</label>
            <div class="formwidget-container">
                <input class="wysiwyg-inline-menu-form-input"
                       type="text"
                       v-model="form.searchAndReplaceSearchTerm"
                       @keydown.enter.prevent.stop="searchNext"
                       @keydown.esc.prevent.stop="close"
                >
            </div>
        </div>
        <div class="wysiwyg-inline-menu-form-actions">
            <a class="wysiwyg-button"
               @click.prevent="searchNext"
            >
                <i class="icon icon-keyboard_arrow_down"></i>
            </a>
            <a class="wysiwyg-button"
               @click.prevent="searchPrevious"
            >
                <i class="icon icon-keyboard_arrow_up"></i>
            </a>
        </div>
        <div class="form-row wysiwyg-inline-menu-form-row">
            <label>Replace</label>
            <div class="formwidget-container">
                <input class="wysiwyg-inline-menu-form-input"
                       type="text"
                       v-model="form.searchAndReplaceReplaceTerm"
                       @keydown.enter.prevent.stop="replace"
                       @keydown.esc.prevent.stop="close"
                >
            </div>
        </div>
        <div class="wysiwyg-inline-menu-form-actions">
            <a class="wysiwyg-button"
               :class="{ 'disabled': getNumResults() == 0 }"
               @click.prevent="replace"
            >
                Replace
            </a>
            <a class="wysiwyg-button"
               :class="{ 'disabled': getNumResults() == 0 }"
               @click.prevent="replaceAll"
            >
                Replace All
            </a>
        </div>
        <div class="wysiwyg-inline-menu-form-actions">
            <a class="wysiwyg-button" @click.prevent="close">
                <i class="icon icon-close"></i>
            </a>
        </div>
    </div>
</template>
<script setup lang="ts">
import {watch} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

const props = defineProps<{
    form: WysiwygForm
}>()

watch([() => props.form.searchAndReplaceSearchTerm], () => {
    props.form.editor.commands.setSearchTerm(props.form.searchAndReplaceSearchTerm);
});

watch([() => props.form.searchAndReplaceReplaceTerm], () => {
    props.form.editor.commands.setReplaceTerm(props.form.searchAndReplaceReplaceTerm);
});

function searchNext()
{
    props.form.editor.commands.nextSearchResult();
}

function searchPrevious()
{
    props.form.editor.commands.previousSearchResult();
}

function replace()
{
    if (getNumResults() > 0) {
        props.form.editor.commands.replace();
    }
}

function replaceAll()
{
    props.form.editor.commands.replaceAll();
}

function close()
{
    props.form.searchAndReplaceSearchTerm = '';
    props.form.searchAndReplaceReplaceTerm = '';
    props.form.editor.commands.setSearchTerm(props.form.searchAndReplaceSearchTerm);
    props.form.editor.commands.setReplaceTerm(props.form.searchAndReplaceReplaceTerm);
    props.form.searchAndReplaceOpen = false;
}

function getNumResults()
{
    if (!props.form.editor) {
        return 0;
    }
    const { results, resultIndex } = props.form.editor.storage.searchAndReplace;
    return results.length;
}

</script>
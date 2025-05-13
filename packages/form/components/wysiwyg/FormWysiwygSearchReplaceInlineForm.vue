<template>
    <div class="wysiwyg-inline-menu-form wysiwyg-search-replace-form">
        <div class="form-row wysiwyg-inline-menu-form-row">
            <label>{{ translator.trans('enhavo_form.wysiwyg_form.command.search.inline_form.label_search', [], 'javascript') }}</label>
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
               :title="translator.trans('enhavo_form.wysiwyg_form.command.search.inline_form.search_next', [], 'javascript')"
            >
                <i class="icon icon-keyboard_arrow_down"></i>
            </a>
            <a class="wysiwyg-button"
               @click.prevent="searchPrevious"
               :title="translator.trans('enhavo_form.wysiwyg_form.command.search.inline_form.search_previous', [], 'javascript')"
            >
                <i class="icon icon-keyboard_arrow_up"></i>
            </a>
        </div>
        <div class="form-row wysiwyg-inline-menu-form-row">
            <label>{{ translator.trans('enhavo_form.wysiwyg_form.command.search.inline_form.label_replace', [], 'javascript') }}</label>
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
                {{ translator.trans('enhavo_form.wysiwyg_form.command.search.inline_form.action_replace', [], 'javascript') }}
            </a>
            <a class="wysiwyg-button"
               :class="{ 'disabled': getNumResults() == 0 }"
               @click.prevent="replaceAll"
            >
                {{ translator.trans('enhavo_form.wysiwyg_form.command.search.inline_form.action_replace_all', [], 'javascript') }}
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
import {inject, watch} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

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
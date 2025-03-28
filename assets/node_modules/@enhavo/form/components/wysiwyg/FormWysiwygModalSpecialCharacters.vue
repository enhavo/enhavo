<template>
    <div class="wysiwyg-modal wysiwyg-special-characters-modal"
         :class="{ 'filter-active': filterValue != '' }"
         @keyup="(event: KeyboardEvent) => { if (event.code === 'Escape') { cancel(); } }"
    >
        <div class="wysiwyg-modal-header">
            {{ translator.trans('enhavo_form.wysiwyg_form.command.special_characters.label', [], 'javascript') }}
        </div>
        <div class="wysiwyg-modal-content">
            <div class="wysiwyg-special-characters-button-filter-container">
                <input class="wysiwyg-special-characters-button-filter"
                       type="text"
                       ref="filter"
                       @keyup="updateFilter"
                       :placeholder="translator.trans('enhavo_form.wysiwyg_form.command.special_characters.label_filter', {}, (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).getTranslationDomain(form))"
                >
            </div>
            <div class="wysiwyg-special-characters-button-tabs">
                <div class="wysiwyg-special-characters-button-tabs-header">
                    <div class="wysiwyg-special-characters-button-tab-header" :class="{ 'active': -1 == activeTabIndex }"
                         @click="activeTabIndex = -1"
                    >
                        {{ translator.trans('enhavo_form.wysiwyg_form.command.special_characters.tabs.all.label', {}, (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).getTranslationDomain(form)) }}
                    </div>
                    <template v-for="(tab, index) in (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).characters">
                        <div class="wysiwyg-special-characters-button-tab-header" :class="{ 'active': index == activeTabIndex }"
                             @click="activeTabIndex = index"
                        >
                            {{ translator.trans(tab.label, {}, (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).getTranslationDomain(form)) }}
                        </div>
                    </template>
                </div>
                <div class="wysiwyg-special-characters-button-tab-content" :class="{ 'active': -1 == activeTabIndex }">
                    <template v-for="(tab, index) in (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).characters">
                        <template v-for="character in tab.characters">
                            <div class="wysiwyg-special-characters-button-character"
                                 :title="translator.trans(character.label, {}, (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).getTranslationDomain(form))"
                                 @click="submit(String.fromCharCode(character.code));"
                                 v-if="!character.duplicate"
                            >
                                {{ String.fromCharCode(character.code) }}
                            </div>
                        </template>
                    </template>
                </div>
                <template v-for="(tab, index) in (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).characters">
                    <div class="wysiwyg-special-characters-button-tab-content" :class="{ 'active': index == activeTabIndex }">
                        <template v-for="character in tab.characters">
                            <div class="wysiwyg-special-characters-button-character"
                                 :title="translator.trans(character.label, {}, (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).getTranslationDomain(form))"
                                 @click="submit(String.fromCharCode(character.code));"
                            >
                                {{ String.fromCharCode(character.code) }}
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            <div class="wysiwyg-special-characters-button-filter-results">
                <template v-for="character in getFiltered()">
                    <div class="wysiwyg-special-characters-button-character"
                         :title="translator.trans(character.label, {}, (form.modal.options['configuration'] as WysiwygSpecialCharactersButton).getTranslationDomain(form))"
                         @click="submit(String.fromCharCode(character.code));"
                    >
                        {{ String.fromCharCode(character.code) }}
                    </div>
                </template>
            </div>
        </div>
        <div class="wysiwyg-modal-actions">
            <a class="btn wysiwyg-modal-action" @click.prevent="cancel">{{ translator.trans('enhavo_form.wysiwyg_form.command.special_characters.modal_cancel', [], 'javascript') }}</a>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject, onMounted, ref} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygSpecialCharactersButton} from "@enhavo/form/wysiwyg/menu/WysiwygSpecialCharactersButton";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    form: WysiwygForm
}>()

const activeTabIndex = ref(-1);
const filterValue = ref('');
const filter = ref();

onMounted(() => {
    (props.form.modal.options['configuration'] as WysiwygSpecialCharactersButton).generateSearchTags(translator, props.form);
    filter.value.focus();
});

function updateFilter()
{
    if (!filter.value) {
        return;
    }
    filterValue.value = filter.value.value;
}

function getFiltered()
{
    let result = [];
    const searchTerm = filterValue.value.toLowerCase();

    for(let tab of (props.form.modal.options['configuration'] as WysiwygSpecialCharactersButton).characters) {
        for(let character of tab.characters) {
            if (character.searchTags.indexOf(searchTerm) > -1) {
                result.push(character);
            }
        }
    }

    return result;
}

function submit(character: string)
{
    props.form.modal.submit({
        'character': character,
    });
}

function cancel()
{
    props.form.modal.cancel();
}

</script>

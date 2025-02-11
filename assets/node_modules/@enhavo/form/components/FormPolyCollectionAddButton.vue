<template>
    <div class="form-poly-collection-add-button-row" v-if="form.allowAdd" v-click-outside="() => { isOpen = false; }">
        <slot name="buttons">
            <div class="form-poly-collection-add-button" @click.prevent="toggleMenu">
                <slot name="add-button">
                    <i class="icon icon-add"></i>
                </slot>
            </div>
        </slot>
        <div v-if="isOpen" class="form-poly-collection-add-menu">
            <div class="form-poly-collection-add-menu-filter-line">
                <input type="text" class="form-poly-collection-add-menu-filter" :placeholder="translator.trans('enhavo_form.label.filter', {}, 'javascript')" ref="filter" @keyup="updateFiltered">
            </div>
            <template v-for="entryLabel of form.entryLabels">
                <div v-if="isAllowed(entryLabel.key) && !entryLabel.filteredOut" @click.prevent="form.addItem(entryLabel.key, addAfter); toggleMenu()" class="add-menu-item">{{ entryLabel.label }}</div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {PolyCollectionForm} from "@enhavo/form/form/model/PolyCollectionForm";
import {inject, ref} from "vue";
import {Translator} from "@enhavo/app/translation/Translator";
import {Form} from "@enhavo/vue-form/model/Form";

const translator = inject<Translator>('translator');

const props = defineProps<{
    form: PolyCollectionForm,
    addAfter?: Form,
}>()
const filter = ref();
const isOpen = ref(false);

function isAllowed(key: string): boolean
{
    return props.form.entryKeys.indexOf(key) >= 0;
}

function toggleMenu() {
    // props.form.toggleMenu();
    isOpen.value = !isOpen.value;
    if (isOpen.value)
    {
        setTimeout(() => {
            updateFiltered();
            filter.value.focus();
        }, 100);
    }
}

function updateFiltered()
{
    if (!filter.value) {
        return;
    }
    for(let entryLabel of props.form.entryLabels) {
        entryLabel.filteredOut = entryLabel.label.toUpperCase().indexOf(filter.value.value.toUpperCase()) === -1;
    }
}

</script>

<template>
    <div class="form-media-item" :ref="(el) => form.setElement(el as HTMLElement)">
        <div class="thumb" @click="toggleEdit">
            <img v-if="isImage(form.file)" :src="form.path('enhavoPreviewThumb')" />
            <div v-else><span class="icon" :class="'icon-'+getIcon(form.file)"></span></div>
        </div>
        <div v-if="deletable" class="delete-button" @click="$emit('delete', form)"><i class="icon icon-close"></i></div>
        <div class="edit-container" ref="editContainer">
            <form-row :form="form.get('filename')" />
            <form-widget :form="form.get('parameters')" />
            <div class="button-row">
                <component v-for="action of form.getActions()" :is="action.component" :data="action"></component>
            </div>
            <form-widget :form="form.get('order')" />
            <form-widget :form="form.get('id')" />
        </div>
    </div>
</template>

<script setup lang="ts">
import {MediaItemForm} from "@enhavo/media/form/model/MediaItemForm";
import {MediaUtil} from "@enhavo/media/form/MediaUtil";
import {ref} from "vue";

const props = defineProps<{
    form: MediaItemForm,
    sortable: boolean,
    deletable: boolean,
}>()

const editContainer = ref();

function isImage(file: File): boolean
{
    return MediaUtil.isImage(props.form.file);
}

function getIcon(file: File): string
{
    return MediaUtil.getIcon(props.form.file);
}

function toggleEdit()
{
    let element = $(editContainer.value);
    element.toggleClass('show');
    if(element.hasClass('show')) {
        let parent = element.parents('[data-form-media]');
        if(parent) {
            element.width(parent.width());
        }
    }
}

</script>

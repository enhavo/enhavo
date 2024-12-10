<template>
    <div class="form-media-item" :class="{ 'edit-show': form.editOpen }" :ref="(el) => form.setElement(el as HTMLElement)" v-click-outside="closeEdit">
        <div class="thumb" @click="toggleEdit">
            <img v-if="isImage(form.file)" :src="form.path('enhavoPreviewThumb')" />
            <div v-else><span class="icon" :class="'icon-'+getIcon(form.file)"></span></div>
        </div>
        <div v-if="deletable" class="delete-button" @click="$emit('delete', form)"><i class="icon icon-close"></i></div>
        <div class="edit-container" ref="editContainer"
             :style="{
                'transform': getEditContainerTransform(),
                'width': editContainerWidth,
            }"
        >
            <form-row :form="form.get('basename')" />
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
import {onMounted, onUnmounted, ref} from "vue";

const props = defineProps<{
    form: MediaItemForm,
    sortable: boolean,
    deletable: boolean,
}>()

const emit = defineEmits(['delete', 'editOpen']);

const editContainer = ref();
const editContainerWidth = ref<string>('100px');

onMounted(() => {
    window.addEventListener('resize', updateEditContainerSize);
    updateEditContainerSize();

    let io = new IntersectionObserver((entries) => {

    }, {});
});

onUnmounted(() => {
    window.removeEventListener('resize', updateEditContainerSize);
});

function updateEditContainerSize()
{
    const parent = $(editContainer.value).closest('[data-form-media]');
    if (parent.length) {
        editContainerWidth.value = parent.get(0).clientWidth + 'px';
    } else {
        editContainerWidth.value = '100px';
    }
}

function isImage(file: File): boolean
{
    return MediaUtil.isImage(props.form.file);
}

function getIcon(file: File): string
{
    return MediaUtil.getIcon(props.form.file);
}

function getEditContainerTransform()
{
    const element = $(props.form.element);
    const parent = element.closest('[data-form-media]');
    if (parent.length) {
        return 'translateX(-' + (element.offset().left - parent.offset().left) + 'px)';
    }
    return 'translateX(0)';
}

function toggleEdit()
{
    if (!props.form.editOpen) {
        emit('editOpen', props.form);
        props.form.editOpen = true;
        updateEditContainerSize();
    } else {
        props.form.editOpen = false;
    }
}

function closeEdit()
{
    props.form.editOpen = false;
}

</script>

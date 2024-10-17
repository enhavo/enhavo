<template>
    <div class="form-media" data-form-media :class="{'multiple': form.multiple}">
        <div class="media-row-container dropzone"
             @dragover.prevent="form.dragover"
             @dragleave.prevent="form.dragleave"
             @dragdrop.prevent="form.dragdrop"
             @drop.prevent="form.drop"
        >
            <span class="indicator icon icon-image"></span>

            <div class="media-items-container">
                <draggable
                    v-model="form.children"
                    item-key="name"
                    @change="event => { form.changeOrder(event) }"
                    @start="event => { form.dragStart(event) }"
                    @end="event => { form.dragEnd(event) }"
                    :group="form.draggableGroup"
                    :handle="form.draggableHandle"
                    tag="ul"
                    :class="'media-items'"
                >
                    <template #item="{ element }">
                        <li>
                            <component
                                :is="form.itemComponent"
                                :form="element"
                                :deletable="form.allowDelete"
                                :sortable="form.multiple && form.sortable"
                                @delete="event => { form.deleteFile(event) }"
                                @up="event => { form.moveItemUp(event) }"
                                @down="event => { form.moveItemDown(event) }"
                            />
                        </li>
                    </template>
                </draggable>
            </div>
        </div>

        <div v-for="upload of form.uploads" class="upload-progress">
            <div class="label">{{ upload.progress }} % <span @click="upload.cancel()" class="icon icon-close"></span></div>
            <div class="bar" :style="'width:'+upload.progress+'%'"></div>
        </div>

        <div class="related-buttons-row">
            <button class="btn has-symbol" v-if="form.upload" @click.prevent="form.startUpload()">{{ form.uploadLabel }} <span class="icon icon-cloud_upload"></span></button>
            <button class="btn has-symbol" v-for="button of form.buttons">{{ button.label }}</button>
        </div>

        <div class="messages">
            <div v-for="error of form.fileErrors">{{ error.message }} <span @click="form.removeError(error)">X</span></div>
        </div>

        <input :id="form.id" type="file" class="upload-input" :multiple="form.multiple" :ref="(el) => form.setElement(el as HTMLElement)" @change.prevent="form.change" style="display: none" />
    </div>
</template>

<script setup lang="ts">
import * as draggableComponent from 'vuedraggable'
import {MediaForm} from "@enhavo/media/form/model/MediaForm";

const draggable = draggableComponent;
const props = defineProps<{
    form: MediaForm
}>()
</script>
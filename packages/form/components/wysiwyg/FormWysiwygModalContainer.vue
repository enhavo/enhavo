<template>
    <div class="wysiwyg-modal-container">
        <template v-if="form.modal !== null">
            <component :is="form.modal.component" :form="form"></component>
        </template>
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

const props = defineProps<{
    form: WysiwygForm
}>()

</script>

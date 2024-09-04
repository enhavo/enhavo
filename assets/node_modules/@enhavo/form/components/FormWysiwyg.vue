<template>
    <div :key="form.id">
        <input type="hidden"
            :name="form.fullName"
            :required="form.required"
            :disabled="form.disabled"
            v-model="form.value"
            :ref="(el) => form.setElement(el as HTMLElement)"
            @change="form.dispatchChange()"
        />
        <div class="wysiwyg-container">
            <div :id="form.editorId" :ref="(el) => {form.editorElement = el as HTMLElement}" v-once></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, onUpdated} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";

const props = defineProps<{
    form: WysiwygForm
}>()

const form = props.form;

onUpdated(() => {
    form.initWysiwyg();
});

onMounted(() => {
    form.initWysiwyg();
});
</script>

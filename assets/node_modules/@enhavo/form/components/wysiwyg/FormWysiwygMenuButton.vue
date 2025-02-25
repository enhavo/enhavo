<template>
    <div class="wysiwyg-button"
         :class="getClass()"
         :title="translator.trans(configuration.getTooltip(form), {}, configuration.getTranslationDomain(form))"
         :aria-label="translator.trans(configuration.getTooltip(form), {}, configuration.getTranslationDomain(form))"
         v-html="getLabel()"
         @click="click"
    >
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuButton} from "../../wysiwyg/WysiwygMenuButton";
import {Translator} from "@enhavo/app/translation/Translator";
import {inject} from "vue";

const translator = inject<Translator>('translator');

const props = defineProps<{
    configuration: WysiwygMenuButton,
    form: WysiwygForm
}>();
const emit = defineEmits(['clicked']);

function click(event: Event) {
    if (props.configuration.click !== null) {
        props.configuration.click(event, props.form);
    }
    emit('clicked');
}

function getLabel(): string {
    let result = '';
    if (props.configuration.icon !== null) {
        if (typeof props.configuration.icon === 'string') {
            result += '<i class="icon icon-' + props.configuration.icon + '"></i>';
        } else {
            result += props.configuration.icon(props.form);
        }
    }
    const label = props.configuration.getLabel(props.form);
    if (label) {
        result += translator.trans(label, [], props.configuration.getTranslationDomain(props.form));
    }
    return result;
}

function getClass(): string {
    let result = '';
    result += props.configuration.getClass(props.form);
    if (props.configuration.getDisabled(props.form)) {
        result += ' disabled';
    }
    return result;
}

</script>

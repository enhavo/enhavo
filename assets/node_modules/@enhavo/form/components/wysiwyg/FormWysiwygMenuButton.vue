<template>
    <div class="wysiwyg-button"
         :class="getClass()"
         :title="getTooltip()"
         v-html="getLabel()"
         @click="click"
    >
    </div>
</template>

<script setup lang="ts">
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuButton} from "../../wysiwyg/WysiwygMenuButton";

const props = defineProps<{
    configuration: WysiwygMenuButton,
    form: WysiwygForm
}>()

function click(event: Event) {
    if (props.configuration.click !== null) {
        props.configuration.click(event, props.form);
    }
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
    if (props.configuration.label !== null) {
        if (typeof props.configuration.label === 'string') {
            result += props.configuration.label;
        } else {
            result += props.configuration.label(props.form);
        }
    }
    return result;
}

function getTooltip()
{
    if (props.configuration.tooltip !== null) {
        if (typeof props.configuration.tooltip === 'string') {
            return props.configuration.tooltip;
        } else {
            return props.configuration.tooltip(props.form);
        }
    }
    return null;
}

function getClass(): string {
    let result = '';
    if (props.configuration.class !== null) {
        if (typeof props.configuration.class === 'string') {
            result += props.configuration.class;
        } else {
            result += props.configuration.class(props.form);
        }
    }
    if (getDisabled()) {
        result += ' disabled';
    }
    return result;
}

function getDisabled(): boolean {
    if (typeof props.configuration.disabled === 'boolean') {
        return props.configuration.disabled;
    } else {
        return props.configuration.disabled(props.form);
    }
}

</script>

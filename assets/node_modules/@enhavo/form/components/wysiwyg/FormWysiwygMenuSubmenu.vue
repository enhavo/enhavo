<template>
    <div class="wysiwyg-submenu"
         :class="getClass()"
         :title="getTooltip()"
         v-click-outside="close"
    >
        <div class="wysiwyg-submenu-label" v-html="getLabel()" @click="clickOrToggleOpen"></div>
        <div class="wysiwyg-submenu-dropdown" @click="toggleOpen"><i class="icon icon-keyboard_arrow_down"></i></div>
        <div class="wysiwyg-submenu-items">
            <template v-for="item in configuration.items">
                <component :is="item.component" :configuration="item" :form="form"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuSubmenu} from "@enhavo/form/wysiwyg/WysiwygMenuSubmenu";

const props = defineProps<{
    configuration: WysiwygMenuSubmenu,
    form: WysiwygForm
}>()

const isOpen = ref(false);

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
    if (typeof props.configuration.class === 'string') {
        result += props.configuration.class;
    } else {
        result += props.configuration.class(props.form);
    }
    if (props.configuration.click !== null) {
        result += ' has-click';
    }
    if (isOpen.value) {
        result += ' open';
    }
    return result;
}

function clickOrToggleOpen(event: Event)
{
    if (props.configuration.click !== null) {
        props.configuration.click(event, props.form);
    } else {
        toggleOpen();
    }
}

function toggleOpen()
{
    isOpen.value = !isOpen.value;
}

function close()
{
    isOpen.value = false;
}

</script>

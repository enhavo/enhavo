<template>
    <div class="wysiwyg-submenu"
         :class="getClass()"
         :title="translator.trans(configuration.getTooltip(form), {}, configuration.getTranslationDomain(form))"
         :aria-label="translator.trans(configuration.getTooltip(form), {}, configuration.getTranslationDomain(form))"
         v-click-outside="close"
    >
        <div class="wysiwyg-submenu-label" v-html="getLabel()" @click="clickOrToggleOpen"></div>
        <div class="wysiwyg-submenu-dropdown" @click="toggleOpen"><i class="icon icon-keyboard_arrow_down"></i></div>
        <div class="wysiwyg-submenu-items" title="">
            <template v-for="item in configuration.items">
                <component :is="item.component" :configuration="item" :form="form" @clicked="childClicked"></component>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject, ref} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygMenuSubmenu} from "@enhavo/form/wysiwyg/menu/WysiwygMenuSubmenu";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    configuration: WysiwygMenuSubmenu,
    form: WysiwygForm
}>();
const emit = defineEmits(['clicked']);

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
    const label = props.configuration.getLabel(props.form);
    if (label) {
        result += translator.trans(label, [], props.configuration.getTranslationDomain(props.form));
    }
    return result;
}

function getClass(): string {
    let result = '';
    result += props.configuration.getClass(props.form);
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

function childClicked()
{
    close();
    emit('clicked');
}

</script>

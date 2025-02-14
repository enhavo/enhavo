<template>
    <div class="wysiwyg-submenu wysiwyg-color-button"
         :class="getClass()"
         :title="translator.trans(configuration.getTooltip(form), {}, configuration.getTranslationDomain(form))"
         :aria-label="translator.trans(configuration.getTooltip(form), {}, configuration.getTranslationDomain(form))"
         v-click-outside="close"
    >
        <div class="wysiwyg-submenu-label" v-html="getLabel()" @click="clickOrToggleOpen"></div>
        <div class="wysiwyg-submenu-dropdown" @click="toggleOpen"><i class="icon icon-keyboard_arrow_down"></i></div>
        <div class="wysiwyg-submenu-items" title="">
            <div class="wysiwyg-color-button-palette">
                <template v-for="color in configuration.colorPalette">
                    <div class="wysiwyg-color-button-palette-color"
                         :class="{ 'active': color === configuration.selectedColor }"
                         :style="'background-color: ' + color"
                         @click="selectColor(color)"
                    ></div>
                </template>
                <div v-if="configuration.customColor !== null"
                     class="wysiwyg-color-button-palette-color wysiwyg-color-button-palette-color-custom"
                     :class="{ 'active': configuration.customColor === configuration.selectedColor }"
                     :style="'background-color: ' + configuration.customColor"
                     @click="selectColor(configuration.customColor)"
                ></div>
            </div>
            <div class="wysiwyg-color-button-special-buttons">
                <div class="wysiwyg-color-button-special-button wysiwyg-color-button-palette-clear"
                     :title="translator.trans(configuration.getLabelClear(form), {}, configuration.getTranslationDomain(form))"
                     @click="selectColor(null)">
                    <i class="icon icon-clear"></i>
                </div>
                <div class="wysiwyg-color-button-special-button wysiwyg-color-button-palette-custom"
                     :title="translator.trans(configuration.getLabelCustom(form), {}, configuration.getTranslationDomain(form))"
                     @click="colorPicker.click()">
                    <i class="icon icon-palette"></i>
                </div>
                <input class="wysiwyg-color-picker-input" type="color" ref="colorPicker"
                       @change="configuration.customColor = colorPicker.value; configuration.selectedColor = configuration.customColor"
                >
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {inject, ref} from "vue";
import {WysiwygForm} from "@enhavo/form/form/model/WysiwygForm";
import {WysiwygColorButton} from "@enhavo/form/wysiwyg/WysiwygColorButton";
import {Translator} from "@enhavo/app/translation/Translator";

const translator = inject<Translator>('translator');

const props = defineProps<{
    configuration: WysiwygColorButton,
    form: WysiwygForm
}>()

const isOpen = ref(false);
const colorPicker = ref();

function selectColor(color: string)
{
    if (color === null) {
        props.configuration.applyClearColor(props.form);
    } else {
        props.configuration.selectedColor = color;
        props.configuration.applySelectedColor(props.form);
    }
    close();
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
    if (result === '') {
        result = 'A';
    }
    if (props.configuration.selectedColor) {
        result = '<div class="wysiwyg-color-button-label" style="border-bottom: 2px solid ' + props.configuration.selectedColor + '">' + result + '</div>';
    } else {
        result = '<div class="wysiwyg-color-button-label">' + result + '</div>';
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

</script>

<template>
    <div class="translation">
        <div :style="{display: 'block'}">
            <form-widget v-for="child of form.children" :form="child" />
        </div>

        <div class="translation-switcher">
            <div class="translation-switcher-current"></div>
            <div class="translation-switcher-menu">
                <div v-for="locale in form.translationLocales" class="translation-switcher-item">{{ locale }}</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {FormTranslation} from "@enhavo/translation/form/model/TranslationForm";

const props = defineProps<{
    form: FormTranslation
}>()

console.log(props.form);
</script>

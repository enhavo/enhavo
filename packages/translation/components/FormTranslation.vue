<template>
    <div class="translation" v-show="form.children.length > 0 && form.children[0].isVisible()">
        <div class="translation-switcher" v-if="form.translationLocales.length > 1">
            <div class="translation-switcher-current">{{ translationManager.locale }}</div>
            <div class="translation-switcher-menu">
                <div v-for="locale in form.translationLocales"
                     class="translation-switcher-item"
                     @click.stop="translationManager.changeLocale(locale)"
                >
                    {{ locale }}
                </div>
            </div>
        </div>

        <div v-for="child of form.children" :style="{display: showForm(child)}">
            <form-row :form="child" />
        </div>
        <form-errors :form="form"></form-errors>
    </div>
</template>

<script setup lang="ts">
import {FormTranslation} from "@enhavo/translation/form/model/TranslationForm";
import "@enhavo/translation/assets/styles/style.scss";
import {inject, onMounted} from "vue";
import {TranslationManager} from "@enhavo/translation/manager/TranslationManager";
import {Form} from "@enhavo/vue-form/model/Form";

const translationManager = inject<TranslationManager>('translationManager');

const props = defineProps<{
    form: FormTranslation
}>()

onMounted(() => {
    translationManager.initLocales(props.form.translationLocales)
})

function showForm(child: Form): string {
    if (child.name == translationManager.locale) {
        return 'block';
    }
    return 'none';
}

</script>

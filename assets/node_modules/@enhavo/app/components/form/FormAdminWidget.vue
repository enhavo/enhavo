<template>
    <div class="formwidget-container">
        <form-compound-widget v-if="form.compound && !form.component" :form="form" v-show="form.visible" />
        <component v-else :is="getComponent()" :form="form" v-show="form.visible" />
    </div>

</template>

<script setup lang="ts">
import {RadioForm} from "@enhavo/vue-form/model/RadioForm";

const props = defineProps<{
    form: RadioForm
}>()

function getComponent(): string
{
    if (props.form.component) {
        return props.form.component;
    }

    return 'form-simple';
}
</script>

<template>
    <component v-if="form.widgetComponent !== 'form-widget'" :form="form" :is="form.widgetComponent" />
    <form-compound-widget v-else-if="form.compound && !form.component" :form="form" />
    <component v-else :is="getComponent()" :form="form" />
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()

function getComponent(): string
{
    if (props.form.component) {
        return props.form.component;
    }

    return 'form-simple';
}
</script>

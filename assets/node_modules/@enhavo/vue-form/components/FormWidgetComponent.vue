<template>
    <form-compound-widget v-if="form.compound && !form.component" :form="form" v-show="form.visible" />
    <component v-else :is="getComponent()" :form="form" v-show="form.visible" />
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

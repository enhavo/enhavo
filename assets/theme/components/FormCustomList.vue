<template>
    <form-list :form="form">
        <template v-slot:item-down-button>

        </template>
    </form-list>
</template>

<script setup lang="ts">
import FormListComponent from "@enhavo/form/components/FormListComponent.vue";
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>

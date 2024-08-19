<template>
    <div class="choice-list">
        <div class="choice-input-container">
            <template v-for="child of form.children">
                <span class="choice-input-with-label">
                    <form-widget :form="child"></form-widget>
                    <form-label :form="child"></form-label>
                </span>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ChoiceForm} from "@enhavo/vue-form/model/ChoiceForm";

const props = defineProps<{
    form: ChoiceForm
}>()

</script>


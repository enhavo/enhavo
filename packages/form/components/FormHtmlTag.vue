<template>
    <div class="html-tag-widget" :ref="(el) => form.setElement(el as HTMLElement)" v-show="form.isVisible()">
        <div class="flex-container">
            <div class="html-tag-widget-text">
                <form-widget :form="form.get('text')" />
            </div>

            <div class="html-tag-widget-choice" v-if="form.has('tag')">
                <form-widget  :form="form.get('tag')" />
            </div>


            <div class="html-tag-widget-choice" v-if="form.has('class')">
                <form-widget  :form="form.get('class')" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {Form} from "@enhavo/vue-form/model/Form";

const props = defineProps<{
    form: Form
}>()
</script>

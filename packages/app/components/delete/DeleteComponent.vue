<template>
    <div class="app-view">
        <view-view></view-view>
        <view-view></view-view>
        <flash-messages></flash-messages>
        <div class="inline-confirm-dialogue">
            <div class="message">{{ getMessage() }}</div>
            <button @click="close" class="btn">{{ getButton() }}</button>
        </div>
    </div>
</template>


<script setup lang="ts">
import { inject } from 'vue'

import '@enhavo/app/assets/styles/view.scss'
import DeleteApp from "@enhavo/app/delete/DeleteApp";
import Translator from "@enhavo/core/Translator";

const deleteApp = inject<DeleteApp>('deleteApp');
const translator = inject<Translator>('translator');

function close()
{
    deleteApp.close()
}

function getMessage()
{
    return translator.trans('enhavo_app.delete.message.success')
}

function getButton()
{
    return translator.trans('enhavo_app.delete.label.close')
}

</script>

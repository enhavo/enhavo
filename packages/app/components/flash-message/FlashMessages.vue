<template>
    <div class="flash-message-container" v-if="flashMessenger && flashMessenger.messages && flashMessenger.messages.length > 0">
        <template v-for="message in flashMessenger.messages">
            <flash-message :message="message" @click="flashMessenger.deleteMessage(message);"></flash-message>
        </template>
    </div>
</template>


<script setup lang="ts">
import {useAsyncService} from "@enhavo/framework/composables/service";
import { FlashMessenger } from "@enhavo/app/flash-message/FlashMessenger";

const flashMessenger = useAsyncService<FlashMessenger>('flashMessenger')
</script>

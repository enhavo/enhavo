<template>
    <div class="flash-message-container" v-if="flashMessenger.messages && flashMessenger.messages.length > 0">
        <template v-for="message in flashMessenger.messages">
            <flash-message :message="message"></flash-message>
        </template>
    </div>
</template>


<script setup lang="ts">
import { inject } from 'vue'
import { FlashMessenger } from "@enhavo/app/flash-message/FlashMessenger";

const flashMessenger = inject<FlashMessenger>('flashMessenger')
</script>

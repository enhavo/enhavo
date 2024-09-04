<template>
    <div v-if="flashMessenger.messages && flashMessenger.messages.length > 0">
        <div class="flash-message-container">
            <template v-for="message in flashMessenger.messages">
                <flash-message v-bind:message="message"></flash-message>
            </template>
        </div>
        <div class="flash-message-placeholders">
            <template v-for="message in flashMessenger.messages">
                <flash-message v-bind:message="message"></flash-message>
            </template>
        </div>
    </div>
</template>


<script setup lang="ts">
import { inject } from 'vue'
import { FlashMessenger } from "@enhavo/app/flash-message/FlashMessenger";

const flashMessenger = inject<FlashMessenger>('flashMessenger')
</script>

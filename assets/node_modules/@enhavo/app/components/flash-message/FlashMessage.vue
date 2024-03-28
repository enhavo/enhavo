<template>
    <div class="flash-message" :class="message.type">
        {{ message.message }}
    </div>
</template>

<script setup lang="ts">
import Message from "@enhavo/app/flash-message/Message";

const props = defineProps<{
    message: Message
}>()
</script>
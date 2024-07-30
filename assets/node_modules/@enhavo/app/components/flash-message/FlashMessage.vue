<template>
    <div class="flash-message" :class="message.type">
        {{ message.message }}
    </div>
</template>

<script setup lang="ts">
import {FlashMessage} from "@enhavo/app/flash-message/FlashMessenger";

const props = defineProps<{
    message: FlashMessage
}>()
</script>
<template>
    <div v-show="flashMessenger.messages && flashMessenger.messages.length > 0">
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

<script lang="ts">
import { Vue, Options, Inject } from "vue-property-decorator";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";

@Options({})
export default class extends Vue
{
    @Inject()
    flashMessenger: FlashMessenger
}
</script>
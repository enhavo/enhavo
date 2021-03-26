<template>
    <div v-show="$flashMessenger.messages && $flashMessenger.messages.length > 0">
        <div class="flash-message-container">
            <template v-for="message in $flashMessenger.messages">
                <flash-message v-bind:message="message"></flash-message>
            </template>
        </div>
        <div class="flash-message-placeholders">
            <template v-for="message in $flashMessenger.messages">
                <flash-message v-bind:message="message"></flash-message>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component } from "vue-property-decorator";

    @Component()
    export default class AppView extends Vue
    {

    }
</script>
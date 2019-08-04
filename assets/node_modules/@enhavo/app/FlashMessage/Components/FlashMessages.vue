<template>
    <div v-show="messages && messages.length > 0">
        <div class="flash-message-container">
            <template v-for="message in messages">
                <flash-message v-bind:message="message"></flash-message>
            </template>
        </div>
        <div class="flash-message-placeholders">
            <template v-for="message in messages">
                <flash-message v-bind:message="message"></flash-message>
            </template>
        </div>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import Message from "@enhavo/app/FlashMessage/Message";
    import FlashMessage from "@enhavo/app/FlashMessage/Components/FlashMessage.vue";

    @Component({
        components: {FlashMessage}
    })
    export default class AppView extends Vue {
        name = 'flash-messages';

        @Prop()
        messages: Message[];
    }
</script>
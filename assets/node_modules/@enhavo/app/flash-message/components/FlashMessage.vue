<template>
    <div class="flash-message" :class="message.type">
        {{ message.message }}
    </div>
</template>

<script lang="ts">
import { Vue, Options, Prop } from "vue-property-decorator";
import Message from "@enhavo/app/flash-message/Message";

@Options({})
export default class extends Vue
{
    @Prop()
    message: Message;
}
</script>
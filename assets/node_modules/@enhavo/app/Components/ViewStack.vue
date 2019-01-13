<template>
    <div>
        Hello
    </div>
</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
@Component
export default class ViewStack extends Vue {
    name: 'view-stack';
}
</script>

<style lang="scss">
    .greeting {
        font-size: 20px;
    }
</style>



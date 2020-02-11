<template>
    <div class="toolbar-dropdown-item" @click="$emit('click')">
        {{ label }}
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class QuickMenuItem extends Vue {
    name: 'quick-menu-item';

    @Prop()
    label: string;
}
</script>






<template>
    <div class="toolbar-dropdown-item" @click="$emit('click')">
        {{ label }}
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class ToolbarDropdownItem extends Vue {
    name: 'toolbar-dropdown-item';

    @Prop()
    label: string;
}
</script>






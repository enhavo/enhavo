<template>
    <div class="toolbar-dropdown-item">
        {{ data.name }}
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class ToolbarDropdownItem extends Vue {
    name: 'toolbar-dropdown-item';

    @Prop()
    data: Object;
}
</script>

<style lang="scss" scoped>
    $toolbar-height: 50px;
        
    .toolbar-dropdown-item { height: $toolbar-height; padding: 10px 20px; box-sizing: border-box; display: flex; align-items: center; justify-content: flex-start; }
</style>







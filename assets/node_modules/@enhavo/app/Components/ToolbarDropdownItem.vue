<template>
    <div class="toolbar-dropdown-item" @click="open()">
        {{ data.name }}
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import eventDispatcher from  '../event-dispatcher';
import { Event } from  '../Event/Event';

@Component
export default class ToolbarDropdownItem extends Vue {
    name: 'toolbar-dropdown-item';

    @Prop()
    data: Object;

    open()
    {
        console.log('open');
        let event = new Event;
        event.name = 'open-view';
        eventDispatcher.dispatch(event);
    }
}
</script>

<style lang="scss" scoped>
    $toolbar-height: 50px;
        
    .toolbar-dropdown-item { height: $toolbar-height; padding: 10px 20px; box-sizing: border-box; display: flex; align-items: center; justify-content: flex-start; }
</style>







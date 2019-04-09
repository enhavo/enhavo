<template>
    <div class="toolbar-dropdown-item" @click="open()">
        {{ data.name }}
    </div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import CreateEvent from '@enhavo/app/ViewStack/Event/CreateEvent';
import ApplicationBag from "@enhavo/app/ApplicationBag";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
let application = <ApplicationInterface>ApplicationBag.getApplication();

@Component
export default class ToolbarDropdownItem extends Vue {
    name: 'toolbar-dropdown-item';

    @Prop()
    data: Object;
    app: App;

    open()
    {
        application.getEventDispatcher().dispatch(new CreateEvent({
            label: 'table',
            component: 'iframe-view',
            url: '/admin/view'
        }));
    }
}
</script>

<style lang="scss" scoped>
    $toolbar-height: 50px;
        
    .toolbar-dropdown-item { height: $toolbar-height; padding: 10px 20px; box-sizing: border-box; display: flex; align-items: center; justify-content: flex-start; }
</style>







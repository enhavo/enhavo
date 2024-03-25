## Create action view

```vue
<template>
    <div class="app-view">
        <view-view v-bind:data="view"></view-view>
        <action-bar v-bind:primary="actions"></action-bar>
    </div>
</template>

<script lang="ts">
    import { Vue, Component, Prop } from "vue-property-decorator";
    import ActionBar from "@enhavo/app/Action/Components/ActionBar.vue";
    import '@enhavo/app/assets/styles/view.scss'
    import ViewData from "@enhavo/app/View/ViewData";
    import ViewComponent from "@enhavo/app/View/Components/ViewComponent";

    @Component({
        components: {ActionBar, 'view-view': ViewComponent}
    })
    export default class AppView extends Vue {
        name = 'app-view';

        @Prop()
        messages: Array<object>;

        @Prop()
        view: ViewData;

        @Prop()
        actions: Array<object>;
    }
</script>
```

```js
import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import ViewApp from "@enhavo/app/ViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";

export default class StatsApp extends ViewApp
{
    private actionManager: ActionManager;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager)
    {
        super(loader, eventDispatcher, view);
        this.actionManager = actionManager;
    }
}
```

```js
import StatsApp from "@enhavo/newsletter/Stats/StatsApp";
import Application from "@enhavo/app/Application";
import AppInterface from "@enhavo/app/AppInterface";
import ActionAwareApplication from "@enhavo/app/Action/ActionAwareApplication";

export class StatsApplication extends Application implements ActionAwareApplication
{
    public getApp(): AppInterface
    {
        if(this.app == null) {
            this.app = new StatsApp(this.getDataLoader(), this.getEventDispatcher(), this.getView(), this.getActionManager());
        }
        return this.app;
    }
}

let application = new StatsApplication();
export default application;
```

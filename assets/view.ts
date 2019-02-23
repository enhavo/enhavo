import dispatcher from '@enhavo/app/ViewStack/dispatcher';
import LoadedEvent from '@enhavo/app/ViewStack/Event/LoadedEvent';
import View from '@enhavo/app/ViewStack/View';
import '@enhavo/app/assets/styles/base.scss'
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import RemoveEvent from "@enhavo/app/ViewStack/Event/RemoveEvent";
import DataLoader from "@enhavo/app/DataLoader";
import Index from '@enhavo/app/Index/Index';
import IndexComponent from '@enhavo/app/Index/Components/IndexComponent.vue';
import VueLoader from "@enhavo/app/VueLoader";

let view = new View(dispatcher);
dispatcher.dispatch(new LoadedEvent(view.getId()));

dispatcher.on('close', (event: CloseEvent) => {
    if(view.getId() === event.id) {
        event.resolve();
        dispatcher.dispatch(new RemoveEvent(view.getId()));
    }
});

const data = new DataLoader('data');
const index = new Index(data);
const v = new VueLoader('app', index, IndexComponent);
v.load();
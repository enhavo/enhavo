import VueLoader from '@enhavo/app/VueLoader';
import DataLoader from '@enhavo/app/DataLoader';
import Main from '@enhavo/app/Table/View';
import AppView from '@enhavo/app/Table/Components/AppView.vue';
import dispatcher from '@enhavo/app/ViewStack/dispatcher';
import LoadedEvent from '@enhavo/app/ViewStack/Event/LoadedEvent';
import CloseEvent from '@enhavo/app/ViewStack/Event/LoadedEvent';
import RemoveEvent from '@enhavo/app/ViewStack/Event/RemoveEvent';
import View from '@enhavo/app/ViewStack/View';

const data = new DataLoader('data');
const main = new Main(data);
const v = new VueLoader('app', main, AppView);
v.load();


let view = new View();
dispatcher.dispatch(new LoadedEvent(view.getId()));

dispatcher.on('close', (event: CloseEvent) => {
    if(view.getId() === event.id) {
        if(window.confirm("close?")) {
            dispatcher.dispatch(new RemoveEvent(view.getId()));
        }
    }
});
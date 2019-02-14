import { VueViewLoader } from '@enhavo/app';
import { DataLoader } from '@enhavo/app';
import { AppView } from '@enhavo/app';
import View from '@enhavo/app/ViewStack/View';
import dispatcher from '@enhavo/app/ViewStack/dispatcher';
import LoadedEvent from '@enhavo/app/ViewStack/Event/LoadedEvent';
import CloseEvent from '@enhavo/app/ViewStack/Event/LoadedEvent';
import RemoveEvent from '@enhavo/app/ViewStack/Event/RemoveEvent';

let data = new DataLoader('data');
let app = new AppView(data);
let v = new VueViewLoader('app', app);
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
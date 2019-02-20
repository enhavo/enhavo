// // Import TinyMCE
// import * as tinymce from 'tinymce/tinymce';
//
// // A theme is also required
// import 'tinymce/themes/modern/theme';
//
// // Any plugins you want to use has to be imported
// import 'tinymce/plugins/paste';
// import 'tinymce/plugins/link';
//
// // Initialize the app
// tinymce.init({
//     selector: '#tiny',
//     plugins: ['paste', 'link']
// });


import VueLoader from '@enhavo/app/VueLoader';
import DataLoader from '@enhavo/app/DataLoader';
import Main from '@enhavo/app/Index/Index';
import AppView from '@enhavo/app/Index/Components/AppView.vue';
import dispatcher from '@enhavo/app/ViewStack/dispatcher';
import LoadedEvent from '@enhavo/app/ViewStack/Event/LoadedEvent';
import CloseEvent from '@enhavo/app/ViewStack/Event/LoadedEvent';
import RemoveEvent from '@enhavo/app/ViewStack/Event/RemoveEvent';
import View from '@enhavo/app/ViewStack/View';

const data = new DataLoader('data');
const main = new Main(data);
const v = new VueLoader('app', main, AppView);
v.load();


let index = new View();
dispatcher.dispatch(new LoadedEvent(index.getId()));

dispatcher.on('close', (event: CloseEvent) => {
    if(index.getId() === event.id) {
        if(window.confirm("close?")) {
            dispatcher.dispatch(new RemoveEvent(index.getId()));
            event.resolve();
        } else {
            event.reject();
        }
    }
});
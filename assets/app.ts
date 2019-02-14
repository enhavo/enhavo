import VueLoader from '@enhavo/app/VueLoader';
import DataLoader from '@enhavo/app/DataLoader';
import Main from '@enhavo/app/Main/Main';
import App from '@enhavo/app/Main/Components/MainComponent.vue';

const data = new DataLoader('data');
const main = new Main(data);
const v = new VueLoader('app', main, App);
v.load();

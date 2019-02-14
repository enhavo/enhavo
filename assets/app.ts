import { VueLoader } from '@enhavo/app';
import { DataLoader } from '@enhavo/app';
import { App } from '@enhavo/app';

const data = new DataLoader('data');
const app = new App(data);
const v = new VueLoader('app', app);
v.load();

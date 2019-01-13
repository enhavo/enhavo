import { VueLoader } from '@enhavo/app'
import { DataLoader } from '@enhavo/app'
import { App } from '@enhavo/app'

let data = new DataLoader('data');
let app = new App(data);
let v = new VueLoader('app', app);
v.load();

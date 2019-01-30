import { VueLoader } from '@enhavo/app'
import { DataLoader } from '@enhavo/app'
import { App } from '@enhavo/app'
import { eventDispatcher } from '@enhavo/app'

let data = new DataLoader('data');
let app = new App(data, eventDispatcher);
let v = new VueLoader('app', app);
v.load();

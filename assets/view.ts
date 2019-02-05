import { VueViewLoader } from '@enhavo/app'
import { DataLoader } from '@enhavo/app'
import { AppView } from '@enhavo/app'
import { eventDispatcher } from '@enhavo/app'

let data = new DataLoader('data');
let app = new AppView(data, eventDispatcher);
let v = new VueViewLoader('app', app);
v.load();

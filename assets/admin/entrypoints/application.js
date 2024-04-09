import container from "../container.di.yaml";
import {Kernel} from "@enhavo/app/kernel/Kernel";
import * as $ from 'jquery';

window.$ = $;
window.jQuery = $;

let kernel = new Kernel(container);
kernel.boot();

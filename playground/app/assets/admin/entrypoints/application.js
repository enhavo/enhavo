import container from "../container.di.yaml";
import {Kernel} from "@enhavo/app/kernel/Kernel";
import $ from 'jquery';
import "fantasticon:icon";

window.$ = $;
window.jQuery = $;

let kernel = new Kernel(container);
kernel.boot();

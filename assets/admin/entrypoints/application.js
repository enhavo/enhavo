import container from "../container.di.yaml";
import {Kernel} from "@enhavo/app/kernel/Kernel";

let kernel = new Kernel(container);
kernel.boot();

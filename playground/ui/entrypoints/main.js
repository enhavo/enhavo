import container from "../container.di.yaml";
import {Kernel} from "@enhavo/app/kernel/Kernel";
import "../styles/styles.scss"
import "@enhavo/ui/styles.scss"

let kernel = new Kernel(container);
kernel.boot();

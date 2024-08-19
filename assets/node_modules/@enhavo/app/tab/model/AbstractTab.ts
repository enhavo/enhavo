import {TabInterface} from "../TabInterface";

export abstract class AbstractTab implements TabInterface
{
    component: string;
    model: string;
    label: string;
    key: string
    active: boolean = false;
    error: boolean = false;
}


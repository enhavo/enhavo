import {TabInterface} from "../TabInterface";

export abstract class AbstractTab implements TabInterface
{
    component: string;
    model: string;
    label: string;
    key: string
    active: boolean = false;
    error: boolean = false;

    morph(source: TabInterface): void
    {
        this.component = source.component;
        this.model = source.model;
        this.label = source.label;
    }

    update(parameters: object) {}
}


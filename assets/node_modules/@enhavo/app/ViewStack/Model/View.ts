import ViewInterface from "../ViewInterface";

export default class View implements ViewInterface
{
    id: number;
    label: string;
    children: ViewInterface[] = [];
    parent: ViewInterface = null;
    component: string;
    priority: number = 0;
    width: string = null;
    loaded: boolean = false;
    minimize: boolean = false;
    customMinimized: boolean = false;
    removed: boolean = false;
    position: number = 0;
    url: string;

    finish(): void {
        this.loaded = true;
    }
}
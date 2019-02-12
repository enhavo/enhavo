import ViewInterface from "../ViewInterface";

export default class View implements ViewInterface
{
    id: number;
    name: string;
    children: ViewInterface[] = [];
    parent: ViewInterface = null;
    loaded: boolean = false;
    component: string;
    width: number;

    finish(): void {
        this.loaded = true;
    }
}
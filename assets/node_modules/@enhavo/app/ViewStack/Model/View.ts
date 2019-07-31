import ViewInterface from "../ViewInterface";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";

export default class View implements ViewInterface
{
    id: number = null;
    label: string = '';
    children: ViewInterface[] = [];
    parent: ViewInterface = null;
    component: string;
    priority: number = 0;
    width: string = null;
    loaded: boolean = false;
    minimize: boolean = false;
    focus: boolean = false;
    customMinimized: boolean = false;
    removed: boolean = false;
    position: number = 0;
    url: string;
    storage: DataStorageEntry[];

    finish(): void {
        this.loaded = true;
    }
}
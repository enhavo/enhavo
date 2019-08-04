import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";
import Item from "@enhavo/app/List/Item";

export default class ListData
{
    public dataRoute: string;
    public dataRouteParameters: object;
    public openRoute: string;
    public openRouteParameters: object;
    public columns: Array<ColumnInterface>;
    public items: Array<Item>;
    public loading: boolean = false;
    public editView: number = null;
    public token: string;
    public dragging: boolean = false;
    public positionProperty: string;
    public parentProperty: string;
    public sortable: boolean;
}

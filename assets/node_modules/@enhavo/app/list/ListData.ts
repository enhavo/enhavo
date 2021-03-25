import ColumnInterface from "@enhavo/app/grid/column/ColumnInterface";
import Item from "@enhavo/app/list/Item";

export default class ListData
{
    public dataRoute: string;
    public dataRouteParameters: object;
    public openRoute: string;
    public openRouteParameters: object;
    public columns: Array<ColumnInterface>;
    public items: Array<Item> = [];
    public loading: boolean = false;
    public editView: number = null;
    public token: string;
    public dragging: boolean = false;
    public positionProperty: string;
    public parentProperty: string;
    public expanded: boolean;
    public sortable: boolean;
    public cssClass: string;
}

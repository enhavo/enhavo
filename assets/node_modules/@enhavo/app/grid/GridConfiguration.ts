
import RowData from "@enhavo/app/grid/column/RowData";
import ColumnInterface from "@enhavo/app/grid/column/ColumnInterface";
import BatchInterface from "@enhavo/app/grid/batch/BatchInterface";
import BatchDataInterface from "@enhavo/app/grid/batch/BatchDataInterface";

export default class GridConfiguration implements BatchDataInterface
{
    public tableRoute: string;
    public tableRouteParameters: object;
    public openRoute: string;
    public openClickable: boolean;
    public openRouteParameters: object;
    public rows: Array<RowData> = [];
    public selectedIds: Array<number> = [];
    public columns: Array<ColumnInterface>;
    public batches: Array<BatchInterface>;
    public batch: string;
    public batchRoute: string;
    public batchRouteParameters: object;
    public loading: boolean = true;
    public page: number = 1;
    public count: number;
    public paginate: boolean;
    public pagination: number;
    public paginationSteps: Array<number>;
    public selectAll: boolean = false;
    public pages: Array<number> = [];
    public cssClass: string;
}

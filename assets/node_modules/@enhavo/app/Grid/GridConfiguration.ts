
import RowData from "@enhavo/app/Grid/Column/RowData";
import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";
import BatchInterface from "@enhavo/app/Grid/Batch/BatchInterface";
import BatchDataInterface from "@enhavo/app/Grid/Batch/BatchDataInterface";

export default class GridConfiguration implements BatchDataInterface
{
    public tableRoute: string;
    public tableRouteParameters: object;
    public openRoute: string;
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
    public paginationSteps: number;
    public selectAll: boolean = false;
    public pages: Array<number> = [];
    public cssClass: string;
}

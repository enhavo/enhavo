
import RowData from "@enhavo/app/Grid/Column/RowData";
import ColumnInterface from "@enhavo/app/Grid/Column/ColumnInterface";
import Batch from "@enhavo/app/Grid/Batch/Batch";

export default class GridConfiguration
{
    public batchRoute: string;
    public tableRoute: string;
    public updateRoute: string;
    public rows: Array<RowData>;
    public columns: Array<ColumnInterface>;
    public batches: Array<Batch>;
    public batch: string = '';
    public loading: boolean = true;
    public page: number = 1;
    public count: number;
    public pagination: number;
    public paginationSteps: number;
    public selectAll: boolean = false;
    public editView: number;
}

import RowData from "@enhavo/app/Grid/Column/RowData";

export default class GridConfiguration
{
    public batchRoute: string;
    public tableRoute: string;
    public rows: Array<RowData>;
    public loading: boolean = true;
    public page: number = 1;
    public count: number;
    public pagination: number;
    public paginationSteps: number;
    public batch: string;
}
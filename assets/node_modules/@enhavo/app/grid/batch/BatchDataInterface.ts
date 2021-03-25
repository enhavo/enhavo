import BatchInterface from "@enhavo/app/grid/batch/BatchInterface";

export default interface BatchDataInterface
{
    batches: Array<BatchInterface>;
    batch: string;
    batchRoute: string;
    batchRouteParameters: object;
}
import BatchInterface from "@enhavo/app/Grid/Batch/BatchInterface";

export default interface BatchDataInterface
{
    batches: Array<BatchInterface>;
    batch: string;
    batchRoute: string;
    batchRouteParameters: object;
}
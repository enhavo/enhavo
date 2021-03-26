import BatchInterface from "@enhavo/app/grid/batch/BatchInterface";

export default abstract class AbstractBatch implements BatchInterface
{
    public key: string;
    public component: string;
    abstract async execute(ids: number[]): Promise<boolean>;
}

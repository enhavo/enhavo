import BatchInterface from "@enhavo/app/grid/batch/BatchInterface";

export abstract class AbstractBatch implements BatchInterface
{
    public key: string;
    public label: string;
    public component: string;
    public model: string;
    abstract execute(ids: number[]): Promise<boolean>;
}

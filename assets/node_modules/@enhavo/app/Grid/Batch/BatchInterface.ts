
export default interface BatchInterface
{
    key: string
    component: string;
    execute(ids: number[]): Promise<boolean>;
}
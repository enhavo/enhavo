
export default interface BatchInterface
{
    key: string
    component: string;
    label: string
    execute(ids: number[]): Promise<boolean>;
}
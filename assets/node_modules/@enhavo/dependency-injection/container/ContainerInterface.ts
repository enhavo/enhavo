
export interface ContainerInterface
{
    get(service: string): Promise<any>;
    init(): Promise<any>;
}

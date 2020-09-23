export interface Container
{
    get(service: string): Promise<any>;
    init(): Promise<any>;
}

declare let service: Container;
export default service;
interface Container
{
    get(service: string): Promise<any>;
}

declare module '@enhavo/dependency-injection'
{
    export default Container;
}

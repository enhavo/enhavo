export default interface FactoryInterface
{
    createFromData(data: object): object;
    createNew(): object
}
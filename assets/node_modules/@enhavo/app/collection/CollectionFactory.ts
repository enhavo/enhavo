import {ModelRegisterFactory} from "@enhavo/app/factory/ModelFactory";
import {FilterInterface} from "@enhavo/app/filter/FilterInterface";
import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";
import {CollectionInterface} from "@enhavo/app/collection/CollectionInterface";
import {RouteContainer} from "@enhavo/app/routing/RouteContainer";
import {BatchInterface} from "../batch/BatchInterface";

export class CollectionFactory extends ModelRegisterFactory
{
    create(name: string,
           data: object,
           filters: FilterInterface[],
           columns: ColumnInterface[],
           batches: BatchInterface[],
           routes: RouteContainer
    ): CollectionInterface
    {
        let collection = this.createNew(name);
        Object.assign(collection, data);
        collection.columns = columns;
        collection.filters = filters;
        collection.batches = batches;
        collection.routes = routes;

        if (typeof collection['onInit'] === 'function') {
            collection['onInit']();
        }

        return collection;
    }

    private createNew(name: string): CollectionInterface
    {
        const entry = this.get(name);
        if (entry === null) {
            throw 'Model "'+name+'" does not exist!';
        }
        return <CollectionInterface>Object.create(entry.getModel());
    }
}

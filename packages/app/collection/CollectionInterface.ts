import {FilterInterface} from "@enhavo/app/filter/FilterInterface";
import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";
import {RouteContainer} from "../routing/RouteContainer";
import {BatchInterface} from "../batch/BatchInterface";

export interface CollectionInterface
{
    filters: FilterInterface[];
    columns: ColumnInterface[];
    batches: BatchInterface[];
    routes: RouteContainer;
    init(): void;
    getIds(): Array<number>;
    load(): Promise<boolean>
}

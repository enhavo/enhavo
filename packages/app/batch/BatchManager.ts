import {BatchFactory} from "./BatchFactory";
import {BatchInterface} from "./BatchInterface";

export class BatchManager
{
    constructor(
        private readonly factory: BatchFactory,
    )
    {
    }

    public createBatches(batches: object[]): BatchInterface[]
    {
        let data = [];
        for (let i in batches) {
            if (!batches[i].hasOwnProperty('model')) {
                throw 'The batch data needs a "model" property!';
            }

            data.push(this.factory.createWithData(batches[i]['model'], batches[i]));
        }
        return data;
    }
}
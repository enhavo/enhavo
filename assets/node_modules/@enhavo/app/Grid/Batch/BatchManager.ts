import Batch from "@enhavo/app/Grid/Batch/Batch";
import * as _ from 'lodash';

export default class FilterManager
{
    private batches: Batch[];

    constructor(batches: Batch[])
    {
        for (let i in batches) {
            batches[i] = _.extend(batches[i], new Batch);
        }
        this.batches = batches;
    }
}
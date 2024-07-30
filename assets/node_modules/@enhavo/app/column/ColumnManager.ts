import {ColumnInterface} from "@enhavo/app/column/ColumnInterface";
import {ColumnFactory} from "@enhavo/app/column/ColumnFactory";

export class ColumnManager
{
    constructor(
        private factory: ColumnFactory
    )
    {
    }


    getSortingParameters(columns: ColumnInterface[])
    {
        let parameters = [];
        for (let column of columns) {
            if(column.directionDesc != null) {
                parameters.push({
                   property: column.sortingProperty,
                   direction:  column.directionDesc ? 'desc' : 'asc'
                });
            }
        }
        return parameters;
    }

    createColumns(columns: object): ColumnInterface[]
    {
        let data = [];
        for (let i in columns) {
            let column = this.factory.createWithData(columns[i].model, columns[i])
            data.push(column);
        }
        return data;
    }
}

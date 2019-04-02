import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import DateTimeColumn from "@enhavo/app/Grid/Column/Model/DateTimeColumn";

export default class DateTimeFactory extends AbstractFactory
{
    createFromData(data: object): DateTimeColumn
    {
        let column = this.createNew();
        let object = <DateTimeColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): DateTimeColumn {
        return new DateTimeColumn();
    }
}

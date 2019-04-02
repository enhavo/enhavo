import AbstractFactory from "@enhavo/app/Grid/Column/Factory/AbstractFactory";
import DateColumn from "@enhavo/app/Grid/Column/Model/DateColumn";

export default class DateFactory extends AbstractFactory
{
    createFromData(data: object): DateColumn
    {
        let column = this.createNew();
        let object = <DateColumn>data;
        column.component = object.component;
        return column;
    }

    createNew(): DateColumn {
        return new DateColumn();
    }
}

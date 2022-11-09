import {Form} from "@enhavo/vue-form/model/Form";

export class FormList extends Form
{
    public border: boolean;
    public sortable: boolean;
    public allowDelete: boolean;
    public allowAdd: boolean;
    public blockName: string;
    public prototype: FormData;
    public prototypeName: string;
    public index: number;
    public itemComponent: string;
    public onDelete: (form: Form) => boolean|Promise<boolean>;
    public onMove: (form: Form) => void;
    public draggableGroup: string
    public draggableHandle: string
}

import {Form} from "@enhavo/vue-form/model/Form"

export class AutoCompleteForm extends Form
{
    public url: string;
    public route: string;
    public routeParameters: object;
    public value: string;
    public multiple: boolean;
    public count: boolean;
    public minimumInputLength: number;
    public placeholder: string;
    public idProperty: string;
    public labelProperty: string;
    public sortable: boolean;
    public editable: boolean;
    public editRoute: string;
    public editRouteParameters: object;
    public viewKey: string;
    public border: boolean;


    public update(recursive: boolean = true)
    {
        super.update(recursive);

        if (this.multiple) {
            this.fullName = this.fullName + '[]';
        }
    }
}

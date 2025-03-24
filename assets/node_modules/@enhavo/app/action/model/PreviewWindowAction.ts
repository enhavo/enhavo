import {AbstractAction} from "@enhavo/app/action/model/AbstractAction";
import {ResourceInputManager} from "@enhavo/app/manager/ResourceInputManager";
import {FormUtil} from "@enhavo/vue-form/form/FormUtil";

export class PreviewWindowAction extends AbstractAction
{
    public apiUrl: string
    public target: string;

    constructor(
        private readonly resourceInputManager: ResourceInputManager,
    ) {
        super();
    }

    async execute(): Promise<void>
    {
        let formData = FormUtil.serializeForm(this.resourceInputManager.form)
        const form = this.createForm(formData)
        document.body.appendChild(form);
        form.submit();
        form.remove();
    }

    private createForm(formData: FormData)
    {
        let form = document.createElement("form");
        form.method = "POST";
        form.action = this.apiUrl;
        form.target = this.target;

        // @ts-ignore URLSearchParams also accepts FormData
        for (let singleData of (new URLSearchParams(formData))) {
            let input = document.createElement("input");
            input.type = 'hidden';
            input.value = singleData[1];
            input.name = singleData[0];
            form.appendChild(input);
        }

        return form;
    }
}

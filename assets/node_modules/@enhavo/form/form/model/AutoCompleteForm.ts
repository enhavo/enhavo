import {Form} from "@enhavo/vue-form/model/Form"
import {Router} from "@enhavo/app/routing/Router";
import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {InputChangedEvent} from "@enhavo/app/manager/ResourceInputManager";

export class AutoCompleteForm extends Form
{
    public url: string;
    public route: string;
    public routeParameters: object;
    public multiple: boolean;
    public count: boolean;
    public minimumInputLength: number;
    public placeholder: string;
    public idProperty: string;
    public labelProperty: string;
    public sortable: boolean;
    public createRoute: string;
    public createRouteParameters: object;
    public editable: boolean;
    public editRoute: string;
    public editRouteParameters: object;
    public editLabel: string;
    public frameKey: string;
    public border: boolean;

    constructor(
        private router: Router,
        private frameManager: FrameManager,
    ) {
        super();
    }

    public registerSubscribers()
    {
        this.frameManager.on('input_changed', async (event) => {
            let frame = await this.frameManager.getFrame(event.origin);
            if (frame.parameters && frame.parameters['fullName'] === this.fullName && frame.key === 'auto_complete_create_edit') {
                this.updateValues((event as InputChangedEvent).resource);
            }
        });
    }

    public update(recursive: boolean = true)
    {
        super.update(recursive);

        if (this.multiple) {
            this.fullName = this.fullName + '[]';
        }
    }

    public openCreate(): void
    {
        let url = this.router.generate(this.createRoute, this.createRouteParameters);
        this.frameManager.openFrame({
            url: url,
            key: 'auto_complete_create_edit',
            parameters: {
                fullName: this.fullName,
            }
        });
    }

    public openEdit(id: string): void
    {
        let parameters = {
            id: id,
        }

        let url = this.router.generate(this.editRoute, parameters);
        this.frameManager.openFrame({
            url: url,
            key: 'auto_complete_create_edit',
            parameters: {
                fullName: this.fullName,
            }
        });
    }

    private updateValues(resource: object)
    {
        for (let i in this.value) {
            if (this.value[i].id == resource[this.idProperty]) {
                this.value[i].text = this.labelProperty ? resource[this.labelProperty] : this.value[i].text;
                this.dispatchChange();
                return;
            }
        }

        this.value.push({
            id: resource[this.idProperty],
            text: resource[this.labelProperty],
        });

        this.dispatchChange();
    }
}

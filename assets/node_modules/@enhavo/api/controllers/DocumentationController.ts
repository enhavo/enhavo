import {Controller} from "@hotwired/stimulus";

export default class extends Controller
{
    static targets = [ "node" ]

    static values = {
        data: Object,
    }

    public nodeTarget: HTMLElement;
    public dataValue: Object;

    async connect()
    {
        await import('swagger-ui/dist/swagger-ui.css');
        let swagger = await import('swagger-ui');
        let SwaggerUI = swagger.default;

        SwaggerUI({
            domNode: this.nodeTarget,
            spec: this.dataValue
        });
    }
}

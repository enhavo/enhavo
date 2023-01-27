import {Controller} from "@hotwired/stimulus";

export default class extends Controller
{
    static targets = [ "node" ]

    public nodeTarget: HTMLElement;

    async connect()
    {
        // @ts-ignore we using webpack import here
        import('swagger-ui/dist/swagger-ui.css');
        // @ts-ignore we using webpack import here
        let swagger = await import('swagger-ui');
        let SwaggerUI = swagger.default;

        SwaggerUI({
            domNode: this.nodeTarget,
            url: "https://petstore.swagger.io/v2/swagger.json"
        });
    }
}

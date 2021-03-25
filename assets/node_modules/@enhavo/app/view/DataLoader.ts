import {Container} from "@enhavo/dependency-injection/container/Container";

export default class DataLoader
{
    private readonly id: string;
    private readonly parameter: string;
    private loaded: boolean = false;
    private container: Container = false;

    constructor(id: string, parameter: string, container: Container)
    {
        this.id = id;
        this.parameter = parameter;
        this.container = container;
        this.init();
    }

    private init()
    {
        if(this.loaded) {
            return;
        }
        let element = document.getElementById(this.id);
        if(element) {
            let data = JSON.parse(document.getElementById(this.id).innerText);
            this.container.setParameter(this.parameter, data)
        }
    }
}

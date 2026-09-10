import {Controller, Application} from "@hotwired/stimulus";
import {ContainerInterface} from "@enhavo/dependency-injection/container/ContainerInterface";

export abstract class AbstractController extends Controller
{
    get (serviceName: string): Promise<any> {
        // @ts-ignore
        return this.application.container.get(serviceName);
    }
}

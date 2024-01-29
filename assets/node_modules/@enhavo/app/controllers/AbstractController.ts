import {Controller, Application} from "@hotwired/stimulus";
import {ContainerInterface} from "@enhavo/dependency-injection/container/ContainerInterface";

export abstract class AbstractController extends Controller
{
    public application: ControllerApplication;
}

declare class ControllerApplication extends Application {
    public container: ContainerInterface;
}

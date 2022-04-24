import {ContainerInterface} from "@enhavo/dependency-injection/container/ContainerInterface";
import {Application} from "@hotwired/stimulus";
import ApplicationController from  "@enhavo/app/controllers/ApplicationController"

export class Kernel
{
    public constructor(
        protected container: ContainerInterface
    )
    {}

    public boot()
    {
        this.container.init().then(() => {
            this.container.get('@hotwired/stimulus/Application').then((app: Application) => {
                app.container = this.container;
                app.start();
            });
        });
    }
}

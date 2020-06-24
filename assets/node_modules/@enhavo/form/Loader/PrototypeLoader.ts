import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import prototypeManager from "@enhavo/form/Prototype/PrototypeManager";

export default class PrototypeLoader extends AbstractLoader
{
    public insert(element: HTMLElement): void
    {
        $(element).find('[data-nested-path]').each((index, nestedElement: HTMLElement) => {
            this.updatePath(nestedElement);
        });
    }

    public move(element: HTMLElement): void
    {
        $(element).find('[data-nested-path]').each((index, nestedElement: HTMLElement) => {
            this.updatePath(nestedElement);
        });
    }

    private updatePath(element: HTMLElement)
    {
        //console.log(element);
    }
}

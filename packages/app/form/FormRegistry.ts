import FormListener from "@enhavo/app/form/FormListener";
import FormInsertEvent from "@enhavo/app/form/event/FormInsertEvent";
import LoaderInterface from "@enhavo/app/form/LoaderInterface";
import FormReleaseEvent from "@enhavo/app/form/event/FormReleaseEvent";
import FormElementEvent from "@enhavo/app/form/event/FormElementEvent";
import FormTypeInterface from "@enhavo/app/form/FormTypeInterface";

export default class FormRegistry
{
    public static INSERT = 'insert';
    public static RELEASE = 'release';
    private static types: FormTypeInterface[] = [];

    public static registerType(type: FormTypeInterface)
    {
        FormRegistry.types.push(type);
    }

    public static getType(element: HTMLElement): FormTypeInterface
    {
        for (let type of FormRegistry.types) {
            if (element === type.getElement()) {
                return type;
            }
        }
        return null;
    }

    register(loader: LoaderInterface): FormRegistry
    {
        FormListener.onInsert((event: FormInsertEvent) => {
            loader.insert(event.getElement());
        });

        FormListener.onRelease((event: FormReleaseEvent) => {
            loader.release(event.getElement());
        });

        FormListener.onMove((event: FormReleaseEvent) => {
            loader.move(event.getElement());
        });

        FormListener.onDrop((event: FormReleaseEvent) => {
            loader.drop(event.getElement());
        });

        FormListener.onRemove((event: FormElementEvent) => {
            loader.remove(event.getElement());
        });

        return this;
    }
}
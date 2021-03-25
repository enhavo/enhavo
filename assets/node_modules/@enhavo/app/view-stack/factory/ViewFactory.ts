import ViewFactoryInterface from "../ViewFactoryInterface";
import View from "../model/View";

export default class ViewFactory implements ViewFactoryInterface
{
    createFromData(data: object): View
    {
        let view = this.createNew();
        let object = <View>data;
        view.id = object.id;
        view.label = object.label;
        view.component = object.component;
        view.storage = object.storage;
        return view;
    }

    createNew(): View {
        let view = new View();
        view.storage = [];
        return view;
    }
}
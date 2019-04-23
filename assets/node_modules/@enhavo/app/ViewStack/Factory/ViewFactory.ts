import ViewFactoryInterface from "../ViewFactoryInterface";
import View from "../Model/View";

export default class ViewFactory implements ViewFactoryInterface
{
    createFromData(data: object): View
    {
        let view = this.createNew();
        let object = <View>data;
        view.id = object.id;
        view.label = object.label;
        view.component = object.component;
        return view;
    }

    createNew(): View {
        return new View()
    }
}
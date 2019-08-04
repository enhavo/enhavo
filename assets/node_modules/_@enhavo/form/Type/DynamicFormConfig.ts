export default class DynamicFormConfig
{
    route: string;
    prototypeName: string;
    collapsed: boolean;
    startLoading: () => void;
    endLoading:  () => void;
}

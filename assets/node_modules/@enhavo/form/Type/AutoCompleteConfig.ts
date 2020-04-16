import AutoCompleteType from "@enhavo/form/Type/AutoCompleteType";

export default class AutoCompleteConfig {
    executeAction: (type: AutoCompleteType, url: string) => void;
    edit: (type: AutoCompleteType, route: string, id: string) => void;
}
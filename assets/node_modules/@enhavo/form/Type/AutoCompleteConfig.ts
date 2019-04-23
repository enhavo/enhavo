import AutoCompleteType from "@enhavo/form/Type/AutoCompleteType";

export default class AutoCompleteConfig {
    create: (type: AutoCompleteType, url: string) => void;
}
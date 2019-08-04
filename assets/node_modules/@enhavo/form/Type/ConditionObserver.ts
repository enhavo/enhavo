import * as $ from "jquery";
import ConditionType from "@enhavo/form/Type/ConditionType";
import ConditionObserverConfig from "@enhavo/form/Type/ConditionObserverConfig";

export default class ConditionObserver
{
    private $element: JQuery;

    private configs: ConditionObserverConfig[];

    private $row: JQuery;

    private subjects: ConditionType[] = [];

    constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.configs = this.$element.data('condition-type-observer');
        let parent = this.$element.parents('[data-form-row]').get(0);
        this.$row = $(parent);

        for (let subject of ConditionType.subjects) {
            let config = this.getConfig(subject);
            if(config !== null) {
                this.subjects.push(subject);
                subject.register(this);
            }
        }
    }

    private getConfig(subject: ConditionType) : ConditionObserverConfig|null
    {
        for (let config of this.configs) {
            if (subject.getId() == config.id) {
                return config;
            }
        }
        return null;
    }

    public wakeUp(subject: ConditionType)
    {
        let condition = null;
        for (let subject of this.subjects) {
            let config = this.getConfig(subject);
            let subjectCondition = config.values.indexOf(subject.getValue()) >= 0;
            if(condition === null) {
                condition = subjectCondition;
            } else {
                if(config.operator == 'and') {
                    condition = condition && subjectCondition;
                } else if(config.operator == 'or') {
                    condition = condition || subjectCondition;
                }
            }
        }

        if(condition) {
            this.show();
        } else {
            this.hide();
        }
    }

    private hide()
    {
        this.$row.hide();
    }

    private show()
    {
        this.$row.show();
    }
}
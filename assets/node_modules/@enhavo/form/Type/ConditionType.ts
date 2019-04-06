import * as $ from 'jquery'
import ConditionTypeConfig from "@enhavo/form/Type/ConditionTypeConfig";
import ConditionObserver from "@enhavo/form/Type/ConditionObserver";

export default class ConditionType
{
    public static subjects: ConditionType[] = [];

    private $element: JQuery;

    private config: ConditionTypeConfig;

    private observers: ConditionObserver[] = [];

    private $input: JQuery;

    constructor(element: HTMLElement)
    {
        ConditionType.subjects.push(this);
        this.$element = $(element);
        this.config = this.$element.data('condition-type');

        let self = this;

        if(this.$element.prop('tagName').toLowerCase() == 'input') {
            this.$input = this.$element;
            this.$input.on('change', function(event) {
                self.notify();
            });
        } else if(this.$element.prop('tagName').toLowerCase() == 'select') {
            this.$input = this.$element;
        } else {
            this.$input = this.$element.find('input');
        }

        this.$input.on('change ifChecked', function(event) {
            self.notify();
        });
    }

    public register(observer: ConditionObserver)
    {
        this.observers.push(observer);
        observer.wakeUp(this);
    }

    public notify()
    {
        for (let observer of this.observers) {
            observer.wakeUp(this);
        }
    }

    public static apply(element:HTMLElement)
    {
        $(element).find('[data-condition-type]').each(function(index, element:HTMLElement) {
            new ConditionType(element);
        });

        $(element).find('[data-condition-type-observer]').each(function(index, element:HTMLElement) {
            new ConditionObserver(element);
        });
    }

    public static init(): void
    {
        $(document).on('formOpenAfter', function (event, element:HTMLElement) {
            ConditionType.apply(element);
        });

        $(document).on('gridAddAfter', function (event, element:HTMLElement) {
            ConditionType.apply(element);
        });

        $(document).on('formListAddItem', function (event, element:HTMLElement) {
            ConditionType.apply(element);
        });
    }

    public getId(): string
    {
        return this.config.id;
    }

    public getValue(): string
    {
        if(this.$input.length > 1) {
            let value = null;
            this.$input.each(function (index, element) {
                if($(element).is(':checked')) {
                    value = $(element).val();
                }
            });
            return value;
        }

        return this.$input.val();
    }
}
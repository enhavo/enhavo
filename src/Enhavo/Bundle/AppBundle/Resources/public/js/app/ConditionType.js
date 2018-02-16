define(["require", "exports", "jquery"], function (require, exports, $) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    var ConditionType = /** @class */ (function () {
        function ConditionType(element) {
            this.observers = [];
            ConditionType.subjects.push(this);
            this.$element = $(element);
            this.config = this.$element.data('condition-type');
            var self = this;
            if (this.$element.prop('tagName').toLowerCase() == 'input') {
                this.$input = this.$element;
                this.$input.on('change', function (event) {
                    self.notify();
                });
            }
            else if (this.$element.prop('tagName').toLowerCase() == 'select') {
                this.$input = this.$element;
            }
            else {
                this.$input = this.$element.find('input');
            }
            this.$input.on('change ifChecked', function (event) {
                self.notify();
            });
        }
        ConditionType.prototype.register = function (observer) {
            this.observers.push(observer);
            observer.wakeUp(this);
        };
        ConditionType.prototype.notify = function () {
            for (var _i = 0, _a = this.observers; _i < _a.length; _i++) {
                var observer = _a[_i];
                observer.wakeUp(this);
            }
        };
        ConditionType.apply = function (element) {
            $(element).find('[data-condition-type]').each(function (index, element) {
                new ConditionType(element);
            });
            $(element).find('[data-condition-type-observer]').each(function (index, element) {
                new ConditionObserver(element);
            });
        };
        ConditionType.init = function () {
            $(document).on('formOpenAfter', function (event, element) {
                ConditionType.apply(element);
            });
            $(document).on('gridAddAfter', function (event, element) {
                ConditionType.apply(element);
            });
            $(document).on('formListAddItem', function (event, element) {
                ConditionType.apply(element);
            });
        };
        ConditionType.prototype.getId = function () {
            return this.config.id;
        };
        ConditionType.prototype.getValue = function () {
            if (this.$input.length > 1) {
                var value_1 = null;
                this.$input.each(function (index, element) {
                    if ($(element).is(':checked')) {
                        value_1 = $(element).val();
                    }
                });
                return value_1;
            }
            return this.$input.val();
        };
        ConditionType.subjects = [];
        return ConditionType;
    }());
    exports.ConditionType = ConditionType;
    var ConditionObserver = /** @class */ (function () {
        function ConditionObserver(element) {
            this.subjects = [];
            this.$element = $(element);
            this.configs = this.$element.data('condition-type-observer');
            var parent = this.$element.parents('[data-form-row]').get(0);
            this.$row = $(parent);
            for (var _i = 0, _a = ConditionType.subjects; _i < _a.length; _i++) {
                var subject = _a[_i];
                var config = this.getConfig(subject);
                if (config !== null) {
                    this.subjects.push(subject);
                    subject.register(this);
                }
            }
        }
        ConditionObserver.prototype.getConfig = function (subject) {
            for (var _i = 0, _a = this.configs; _i < _a.length; _i++) {
                var config = _a[_i];
                if (subject.getId() == config.id) {
                    return config;
                }
            }
            return null;
        };
        ConditionObserver.prototype.wakeUp = function (subject) {
            var condition = null;
            for (var _i = 0, _a = this.subjects; _i < _a.length; _i++) {
                var subject_1 = _a[_i];
                var config = this.getConfig(subject_1);
                var subjectCondition = config.values.indexOf(subject_1.getValue()) >= 0;
                if (condition === null) {
                    condition = subjectCondition;
                }
                else {
                    if (config.operator == 'and') {
                        condition = condition && subjectCondition;
                    }
                    else if (config.operator == 'or') {
                        condition = condition || subjectCondition;
                    }
                }
            }
            if (condition) {
                this.show();
            }
            else {
                this.hide();
            }
        };
        ConditionObserver.prototype.hide = function () {
            this.$row.hide();
        };
        ConditionObserver.prototype.show = function () {
            this.$row.show();
        };
        return ConditionObserver;
    }());
    exports.ConditionObserver = ConditionObserver;
    ConditionType.init();
});

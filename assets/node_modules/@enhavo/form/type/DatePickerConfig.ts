import $ from "jquery";
import * as _ from "lodash";

export default class DatePickerConfig
{
    static set(type: string, config: any) {
        $(document).on('datePickerInit', (event, data: any) => {
            if(type == data.configType) {
                _.merge(data.options, config);
            }
        });
    }
}
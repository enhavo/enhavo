import * as $ from "jquery";
import InitializerInterface from "@enhavo/app/InitializerInterface";

export default class Cart implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        $(element).find('[data-select-amount] select').on('change', function() {
            if($(this).children("option:selected").val() == '+') {
                $(this).next('[data-more-amount]').fadeIn();
                $(this).next('[data-more-amount]').focus();
            }
        });

        $(element).find('[data-more-amount]').on('blur', function() {
            if ($(this).val() != 0) {
                let value = $(this).val();
                $(this).parent('[data-select-amount]').find('select').append(new Option(value, value));
                $(this).parent('[data-select-amount]').find('select').val(value);
            }
            $(this).fadeOut();
        });
    }
}

import { Controller } from "@hotwired/stimulus"

export default class extends Controller
{

}

// $(element).find('[data-shop-select-amount] select').on('change', function() {
//     if($(this).children("option:selected").val() == '+') {
//         $(this).next('[data-shop-amount-more]').fadeIn();
//         $(this).next('[data-shop-amount-more]').focus();
//     }
// });
//
// $(element).find('[data-shop-amount-more]').on('blur', function() {
//     if ($(this).val() != 0) {
//         let value = <string>$(this).val();
//         $(this).parent('[data-shop-amount-more]').find('select').append(new Option(value, value));
//         $(this).parent('[data-shop-amount-more]').find('select').val(value);
//     }
//     $(this).fadeOut();
// });
//
// $(element).find('[data-shop-add-cart-item]').on('click', function(e) {
//     e.preventDefault();
//
// });

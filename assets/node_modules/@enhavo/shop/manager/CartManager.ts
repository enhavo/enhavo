import {ProductVariant} from "@enhavo/shop/model/ProductVariant";
import axios from 'axios';

export class CartManager
{
    public addToCart(productVariant: ProductVariant, quantity: number = 1)
    {
        let uri = '/cart/add/' + productVariant.id;

        axios.post(uri, {
            'add_to_cart': {
                'cartItem': {
                    'quantity': quantity
                }
            }
        })
            .then((response) => {
                console.log(response)

            })
            .catch((error) => {
                console.error(error)
            })
    }
}


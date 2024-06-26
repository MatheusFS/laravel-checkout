# Checkout with Pagar.me

Laravel checkout facade to Pagar.me v4 api

![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/matheusfs/laravel-checkout/tests.yml)
[![codecov](https://codecov.io/gh/MatheusFS/laravel-checkout/graph/badge.svg?token=M2Z0NW32LQ)](https://codecov.io/gh/MatheusFS/laravel-checkout)

#### Test coverage map
![](https://codecov.io/gh/MatheusFS/laravel-checkout/graphs/tree.svg?token=M2Z0NW32LQ)

## Instalation
```bash
composer require matheusfs/laravel-checkout
```
```bash
php artisan vendor:publish --provider="MatheusFS\Laravel\Checkout\CheckoutServiceProvider" --tag="config"
```

## Simple payment link redirect

```php
public function buy(Request $request, $id) {

    # Request creates objects from inputs name
    $shipping = (object) $request->shipping;
    $customer = (object) $request->customer;

    $item = Product::find($id); # your product model goes here

    # Normalize address w/ Pagar.me v4 API
    $address = new Address(
        $shipping->street,
        $shipping->street_number,
        $shipping->zipcode,
        'br', # Default country, if you operate w/ more than one country you can modify the request to ask user
        $shipping->state,
        $shipping->city,
        $shipping->neighborhood,
        $shipping->complementary
    );

    # Normalize shipping w/ Pagar.me v4 API
    $Shipping = new Shipping($shipping->name, $address, $shipping->fee, new DateTime());

    # Initiate facade
    $pagarme = new Checkout();

    # Normalize customer and billing w/ Pagar.me v4 API
    $pagarme->setCustomer($customer->name, $customer->cpf, $customer->phone_number, $customer->email);
    $pagarme->setBilling('Entrega', $address);
    $pagarme->setShipping($Shipping);

    # Add normalized item to checkout
    $pagarme->addItem($item->id, $item->label, $item->price);

    # Redirect the user to the generated payment link
    return redirect($pagarme->getPaymentLink($item->price + $shipping->fee));
}
```
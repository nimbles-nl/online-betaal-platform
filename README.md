# PHP client for onlinebetaalplatform.nl

[![Build Status](https://travis-ci.org/nimbles-nl/online-betaal-platform.svg?branch=master)](https://travis-ci.org/nimbles-nl/online-betaal-platform) [![Latest Stable Version](https://poser.pugx.org/nimbles-nl/online-betaal-platform/v/stable)](https://packagist.org/packages/nimbles-nl/online-betaal-platform) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nimbles-nl/online-betaal-platform/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nimbles-nl/online-betaal-platform/?branch=master) [![License](https://poser.pugx.org/nimbles-nl/online-betaal-platform/license)](https://packagist.org/packages/nimbles-nl/online-betaal-platform) [![Total Downloads](https://poser.pugx.org/nimbles-nl/online-betaal-platform/downloads)](https://packagist.org/packages/nimbles-nl/online-betaal-platform) [![codecov](https://codecov.io/gh/nimbles-nl/online-betaal-platform/branch/master/graph/badge.svg)](https://codecov.io/gh/nimbles-nl/online-betaal-platform)

- IN DEVELOPMENT
Do not use in production
Many featured are not implemented yet and many stuff might change

### Step 1: Download the package using composer

Install package by running the command:

``` bash
$ composer require nimbles-nl/online-betaal-platform
```

Initializing OnlineBetaalPlatform
---------------------------------

``` php
$guzzle = new Client();
$apiToken = 'secret-token';
$url = 'https://api-sandbox.onlinebetaalplatform.nl/v1';
$merchantUid = 'secret-uuid';

$onlineBetaalPlatform = new OnlineBetaalPlatform($guzzle, $apiToken, $url, $merchantUid);
```

Send a payment request
----------------------

``` php
$amount = 10050; // in cents 100 = 1 euro.
$payment = new Payment('https://www.mywebsite.nl/return-url', $amount);

$product = new Product('Apple pie', 950, 1);
$payment->addProduct($product);

$payment = $onlineBetaalPlatform->createTransaction($payment);

$payment->getUid();  // remember this uuid..

return new RedirectResponse($payment->getRedirectUrl());
```

Receive a payment request
-------------------------

``` php
$uuid = 'uuid-received-from-create-method-above';

$payment = $onlineBetaalPlatform->getTransaction($uuid);

if ($payment->isSuccess()) {
    // Your payment is successful
} else {
    // Oops try again..
}

```

Receive Payments
-------------------------

``` php
$payments = $onlineBetaalPlatform->getTransactions();
```

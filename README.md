# PHP client for onlinebetaalplatform.nl

[![Build Status](https://travis-ci.org/nimbles-nl/online-betaal-platform.svg?branch=master)](https://travis-ci.org/nimbles-nl/online-betaal-platform) [![Coverage Status](https://coveralls.io/repos/github/nimbles-nl/online-betaal-platform/badge.svg?branch=master)](https://coveralls.io/github/nimbles-nl/online-betaal-platform?branch=master)

### Step 1: Download the package using composer

Install package by running the command:

``` bash
$ composer require nimbles-nl/online-betaal-platform
```

Send a payment request
----------------------

``` php
$guzzle = new Client();
$apiToken = 'secret-token';
$url = 'https://api-sandbox.onlinebetaalplatform.nl/v1';
$merchantUid = 'secret-uuid';

$onlineBetaalPlatform = new OnlineBetaalPlatform($guzzle, $apiToken, $url, $merchantUid);

$amount = 10050;  // in full cents. 100 = 1 euro.
$payment = new Payment('https://www.mywebsite.nl/return-url', $amount);
$payment = $onlineBetaalPlatform->createTransaction($payment);

$payment->getUid();  // remember this uuid..

return new RedirectResponse($payment->getRedirectUrl());
```

Receive a payment request
-------------------------

``` php
$guzzle = new Client();
$apiToken = 'secret-token';
$url = 'https://api-sandbox.onlinebetaalplatform.nl/v1';
$merchantUid = 'secret-uuid';

$onlineBetaalPlatform = new OnlineBetaalPlatform($guzzle, $apiToken, $url, $merchantUid);

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
$guzzle = new Client();
$apiToken = 'secret-token';
$url = 'https://api-sandbox.onlinebetaalplatform.nl/v1';
$merchantUid = 'secret-uuid';

$onlineBetaalPlatform = new OnlineBetaalPlatform($guzzle, $apiToken, $url, $merchantUid);

$payments = $onlineBetaalPlatform->getTransactions();
```

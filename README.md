# PHP client for onlinebetaalplatform.nl

[![Build Status](https://travis-ci.org/nimbles-nl/online-betaal-platform.svg?branch=master)](https://travis-ci.org/nimbles-nl/online-betaal-platform) [![Latest Stable Version](https://poser.pugx.org/nimbles-nl/online-betaal-platform/v/stable)](https://packagist.org/packages/nimbles-nl/online-betaal-platform) [![License](https://poser.pugx.org/nimbles-nl/online-betaal-platform/license)](https://packagist.org/packages/nimbles-nl/online-betaal-platform) [![Total Downloads](https://poser.pugx.org/nimbles-nl/online-betaal-platform/downloads)](https://packagist.org/packages/nimbles-nl/online-betaal-platform) [![Coverage Status](https://coveralls.io/repos/github/nimbles-nl/online-betaal-platform/badge.svg?branch=master)](https://coveralls.io/github/nimbles-nl/online-betaal-platform?branch=master)

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

$onlinePayment = new OnlineBetaalPlatform($guzzle, $apiToken, $url, $merchantUid);

$payment = new Payment('https://www.mywebsite.nl/return-url', 10050);
$payment = $onlinePayment->createTransaction($payment);

return new RedirectResponse($payment->getRedirectUrl());
```

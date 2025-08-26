# OpenAPI\Client\DomainModelApi

All URIs are relative to https://fenix.tecnico.ulisboa.pt/api/fenix/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getDomainModel()**](DomainModelApi.md#getDomainModel) | **GET** /domainModel | Get the application&#39;s domain model (DML) representation. |


## `getDomainModel()`

```php
getDomainModel($lang): \OpenAPI\Client\Model\DomainModelResponse
```

Get the application's domain model (DML) representation.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DomainModelApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getDomainModel($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DomainModelApi->getDomainModel: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\DomainModelResponse**](../Model/DomainModelResponse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

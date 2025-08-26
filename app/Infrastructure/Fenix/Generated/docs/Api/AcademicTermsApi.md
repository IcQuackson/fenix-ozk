# OpenAPI\Client\AcademicTermsApi

All URIs are relative to https://fenix.tecnico.ulisboa.pt/api/fenix/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**listAcademicTerms()**](AcademicTermsApi.md#listAcademicTerms) | **GET** /academicterms | List all academic terms. |


## `listAcademicTerms()`

```php
listAcademicTerms($lang): array<string,string[]>
```

List all academic terms.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\AcademicTermsApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->listAcademicTerms($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AcademicTermsApi->listAcademicTerms: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

**array<string,string[]>**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

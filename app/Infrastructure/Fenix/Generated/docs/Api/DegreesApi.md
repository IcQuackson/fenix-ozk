# OpenAPI\Client\DegreesApi

All URIs are relative to https://fenix.tecnico.ulisboa.pt/api/fenix/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getDegreeById()**](DegreesApi.md#getDegreeById) | **GET** /degrees/{id} | Get degree by ID. |
| [**listDegreeCourses()**](DegreesApi.md#listDegreeCourses) | **GET** /degrees/{id}/courses | List courses for a degree. |
| [**listDegrees()**](DegreesApi.md#listDegrees) | **GET** /degrees | List degrees. |


## `getDegreeById()`

```php
getDegreeById($id, $academic_term, $lang): \OpenAPI\Client\Model\Degree
```

Get degree by ID.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DegreesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$academic_term = 2013/2014; // string | One of the terms returned by `/academicterms`.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getDegreeById($id, $academic_term, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DegreesApi->getDegreeById: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **academic_term** | **string**| One of the terms returned by &#x60;/academicterms&#x60;. | [optional] |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Degree**](../Model/Degree.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listDegreeCourses()`

```php
listDegreeCourses($id, $academic_term, $lang): \OpenAPI\Client\Model\DegreeCourse[]
```

List courses for a degree.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DegreesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$academic_term = 2013/2014; // string | One of the terms returned by `/academicterms`.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->listDegreeCourses($id, $academic_term, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DegreesApi->listDegreeCourses: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **academic_term** | **string**| One of the terms returned by &#x60;/academicterms&#x60;. | [optional] |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\DegreeCourse[]**](../Model/DegreeCourse.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listDegrees()`

```php
listDegrees($academic_term, $lang): \OpenAPI\Client\Model\Degree[]
```

List degrees.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\DegreesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$academic_term = 2013/2014; // string | One of the terms returned by `/academicterms`.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->listDegrees($academic_term, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DegreesApi->listDegrees: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **academic_term** | **string**| One of the terms returned by &#x60;/academicterms&#x60;. | [optional] |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Degree[]**](../Model/Degree.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

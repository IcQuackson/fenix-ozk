# OpenAPI\Client\SpacesApi

All URIs are relative to https://fenix.tecnico.ulisboa.pt/api/fenix/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getSpaceBlueprint()**](SpacesApi.md#getSpaceBlueprint) | **GET** /spaces/{id}/blueprint | Get a space&#39;s blueprint. |
| [**getSpaceById()**](SpacesApi.md#getSpaceById) | **GET** /spaces/{id} | Get a space by ID (campus, building, floor, or room). |
| [**listSpaces()**](SpacesApi.md#listSpaces) | **GET** /spaces | List campi. |


## `getSpaceBlueprint()`

```php
getSpaceBlueprint($id, $format, $lang): \SplFileObject
```

Get a space's blueprint.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\SpacesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$format = 'format_example'; // string | Desired output format.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getSpaceBlueprint($id, $format, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SpacesApi->getSpaceBlueprint: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **format** | **string**| Desired output format. | |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

**\SplFileObject**

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `image/jpeg`, `application/dwg`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getSpaceById()`

```php
getSpaceById($id, $day, $lang): \OpenAPI\Client\Model\SpaceDetail
```

Get a space by ID (campus, building, floor, or room).

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\SpacesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$day = 21/02/2014; // string | Day to filter room events, in `dd/MM/yyyy` format.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getSpaceById($id, $day, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SpacesApi->getSpaceById: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **day** | **string**| Day to filter room events, in &#x60;dd/MM/yyyy&#x60; format. | [optional] |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\SpaceDetail**](../Model/SpaceDetail.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listSpaces()`

```php
listSpaces($lang): \OpenAPI\Client\Model\SpaceSummary[]
```

List campi.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\SpacesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->listSpaces($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SpacesApi->listSpaces: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\SpaceSummary[]**](../Model/SpaceSummary.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

# OpenAPI\Client\CoursesApi

All URIs are relative to https://fenix.tecnico.ulisboa.pt/api/fenix/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getCourseById()**](CoursesApi.md#getCourseById) | **GET** /courses/{id} | Get course details by ID. |
| [**getCourseSchedule()**](CoursesApi.md#getCourseSchedule) | **GET** /courses/{id}/schedule | Get schedule for a course. |
| [**listCourseEvaluations()**](CoursesApi.md#listCourseEvaluations) | **GET** /courses/{id}/evaluations | List evaluations for a course. |
| [**listCourseGroups()**](CoursesApi.md#listCourseGroups) | **GET** /courses/{id}/groups | List groups for a course. |
| [**listCourseStudents()**](CoursesApi.md#listCourseStudents) | **GET** /courses/{id}/students | List students attending a course. |


## `getCourseById()`

```php
getCourseById($id, $lang): \OpenAPI\Client\Model\Course
```

Get course details by ID.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CoursesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getCourseById($id, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CoursesApi->getCourseById: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Course**](../Model/Course.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getCourseSchedule()`

```php
getCourseSchedule($id, $lang): \OpenAPI\Client\Model\CourseSchedule
```

Get schedule for a course.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CoursesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getCourseSchedule($id, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CoursesApi->getCourseSchedule: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\CourseSchedule**](../Model/CourseSchedule.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listCourseEvaluations()`

```php
listCourseEvaluations($id, $lang): \OpenAPI\Client\Model\Evaluation[]
```

List evaluations for a course.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CoursesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->listCourseEvaluations($id, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CoursesApi->listCourseEvaluations: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Evaluation[]**](../Model/Evaluation.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listCourseGroups()`

```php
listCourseGroups($id, $lang): \OpenAPI\Client\Model\Group[]
```

List groups for a course.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CoursesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->listCourseGroups($id, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CoursesApi->listCourseGroups: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Group[]**](../Model/Group.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listCourseStudents()`

```php
listCourseStudents($id, $lang): \OpenAPI\Client\Model\CourseStudents
```

List students attending a course.

Public endpoint.

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new OpenAPI\Client\Api\CoursesApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 'id_example'; // string
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->listCourseStudents($id, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling CoursesApi->listCourseStudents: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\CourseStudents**](../Model/CourseStudents.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

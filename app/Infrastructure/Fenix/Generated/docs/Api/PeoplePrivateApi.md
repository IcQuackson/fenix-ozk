# OpenAPI\Client\PeoplePrivateApi

All URIs are relative to https://fenix.tecnico.ulisboa.pt/api/fenix/v1, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**getPerson()**](PeoplePrivateApi.md#getPerson) | **GET** /person | Get the current person&#39;s information. |
| [**getPersonCalendarClasses()**](PeoplePrivateApi.md#getPersonCalendarClasses) | **GET** /person/calendar/classes | Get the user&#39;s class calendar. |
| [**getPersonCalendarEvaluations()**](PeoplePrivateApi.md#getPersonCalendarEvaluations) | **GET** /person/calendar/evaluations | Get the user&#39;s evaluation calendar. |
| [**getPersonCourses()**](PeoplePrivateApi.md#getPersonCourses) | **GET** /person/courses | Get the user&#39;s courses. |
| [**getPersonCurriculum()**](PeoplePrivateApi.md#getPersonCurriculum) | **GET** /person/curriculum | Get the user&#39;s complete curriculum (students only). |
| [**getPersonEvaluations()**](PeoplePrivateApi.md#getPersonEvaluations) | **GET** /person/evaluations | Get the user&#39;s written evaluations. |
| [**getPersonPayments()**](PeoplePrivateApi.md#getPersonPayments) | **GET** /person/payments | Get the user&#39;s payments information. |
| [**updatePersonEvaluationEnrolment()**](PeoplePrivateApi.md#updatePersonEvaluationEnrolment) | **PUT** /person/evaluations/{id} | Enrol or disenrol from a written evaluation by ID. |


## `getPerson()`

```php
getPerson($lang): \OpenAPI\Client\Model\Person
```

Get the current person's information.

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getPerson($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->getPerson: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Person**](../Model/Person.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonCalendarClasses()`

```php
getPersonCalendarClasses($format, $lang): \OpenAPI\Client\Model\GetPersonCalendarClasses200Response
```

Get the user's class calendar.

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$format = 'json'; // string | Response format for calendar endpoints.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getPersonCalendarClasses($format, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->getPersonCalendarClasses: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **format** | **string**| Response format for calendar endpoints. | [optional] [default to &#39;json&#39;] |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\GetPersonCalendarClasses200Response**](../Model/GetPersonCalendarClasses200Response.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `text/calendar`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonCalendarEvaluations()`

```php
getPersonCalendarEvaluations($format, $lang): \OpenAPI\Client\Model\GetPersonCalendarEvaluations200Response
```

Get the user's evaluation calendar.

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$format = 'json'; // string | Response format for calendar endpoints.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getPersonCalendarEvaluations($format, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->getPersonCalendarEvaluations: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **format** | **string**| Response format for calendar endpoints. | [optional] [default to &#39;json&#39;] |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\GetPersonCalendarEvaluations200Response**](../Model/GetPersonCalendarEvaluations200Response.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`, `text/calendar`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonCourses()`

```php
getPersonCourses($academic_term, $lang): \OpenAPI\Client\Model\PersonCoursesResponse
```

Get the user's courses.

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$academic_term = 2013/2014; // string | One of the terms returned by `/academicterms`.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getPersonCourses($academic_term, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->getPersonCourses: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **academic_term** | **string**| One of the terms returned by &#x60;/academicterms&#x60;. | [optional] |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\PersonCoursesResponse**](../Model/PersonCoursesResponse.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonCurriculum()`

```php
getPersonCurriculum($lang): \OpenAPI\Client\Model\PersonCurriculumItem[]
```

Get the user's complete curriculum (students only).

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getPersonCurriculum($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->getPersonCurriculum: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\PersonCurriculumItem[]**](../Model/PersonCurriculumItem.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonEvaluations()`

```php
getPersonEvaluations($lang): \OpenAPI\Client\Model\Evaluation[]
```

Get the user's written evaluations.

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getPersonEvaluations($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->getPersonEvaluations: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Evaluation[]**](../Model/Evaluation.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getPersonPayments()`

```php
getPersonPayments($lang): \OpenAPI\Client\Model\PaymentsResponse
```

Get the user's payments information.

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getPersonPayments($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->getPersonPayments: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\PaymentsResponse**](../Model/PaymentsResponse.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `updatePersonEvaluationEnrolment()`

```php
updatePersonEvaluationEnrolment($id, $enrol, $lang): \OpenAPI\Client\Model\Evaluation[]
```

Enrol or disenrol from a written evaluation by ID.

Private endpoint (requires user authorization).

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


// Configure Bearer (JWT) authorization: bearerAuth
$config = OpenAPI\Client\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new OpenAPI\Client\Api\PeoplePrivateApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$id = 'id_example'; // string
$enrol = 'enrol_example'; // string | Enrol or disenrol from a written evaluation.
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->updatePersonEvaluationEnrolment($id, $enrol, $lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PeoplePrivateApi->updatePersonEvaluationEnrolment: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **string**|  | |
| **enrol** | **string**| Enrol or disenrol from a written evaluation. | |
| **lang** | **string**| BCP 47 language tag. If supported, localizes the response. | [optional] |

### Return type

[**\OpenAPI\Client\Model\Evaluation[]**](../Model/Evaluation.md)

### Authorization

[bearerAuth](../../README.md#bearerAuth)

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

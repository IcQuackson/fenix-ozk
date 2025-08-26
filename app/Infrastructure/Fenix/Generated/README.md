# OpenAPIClient-php

OpenAPI specification derived from the provided FenixEdu API documentation.

**Scope & availability**
- Applicable to all FenixEdu installations as of **v1.2.0**.
- Availability and access control may vary per institution. Check your local instance.

**Localization**
- All endpoints accept an optional `lang` query parameter. If present and supported,
  responses are localized; otherwise, the default language is used.
- Available languages are returned by `GET /about`.

**Security**
- The original text does not describe authentication details. This spec marks endpoints under `/person` as **private** and attaches a generic Bearer token scheme. Replace or extend the security scheme to match your deployment (e.g., OAuth2 Authorization Code).



## Installation & Usage

### Requirements

PHP 8.1 and later.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/OpenAPIClient-php/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new OpenAPI\Client\Api\AboutApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$lang = en-US; // string | BCP 47 language tag. If supported, localizes the response.

try {
    $result = $apiInstance->getAbout($lang);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AboutApi->getAbout: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://fenix.tecnico.ulisboa.pt/api/fenix/v1*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AboutApi* | [**getAbout**](docs/Api/AboutApi.md#getabout) | **GET** /about | Get institution metadata, RSS feeds, languages, and current term.
*AcademicTermsApi* | [**listAcademicTerms**](docs/Api/AcademicTermsApi.md#listacademicterms) | **GET** /academicterms | List all academic terms.
*ContactsFacilitiesApi* | [**getCanteenMenu**](docs/Api/ContactsFacilitiesApi.md#getcanteenmenu) | **GET** /canteen | Get Alameda canteen menu.
*ContactsFacilitiesApi* | [**getContacts**](docs/Api/ContactsFacilitiesApi.md#getcontacts) | **GET** /contacts | Get institutional contacts.
*ContactsFacilitiesApi* | [**getParking**](docs/Api/ContactsFacilitiesApi.md#getparking) | **GET** /parking | Get parking information.
*ContactsFacilitiesApi* | [**getShuttle**](docs/Api/ContactsFacilitiesApi.md#getshuttle) | **GET** /shuttle | Get shuttle information.
*CoursesApi* | [**getCourseById**](docs/Api/CoursesApi.md#getcoursebyid) | **GET** /courses/{id} | Get course details by ID.
*CoursesApi* | [**getCourseSchedule**](docs/Api/CoursesApi.md#getcourseschedule) | **GET** /courses/{id}/schedule | Get schedule for a course.
*CoursesApi* | [**listCourseEvaluations**](docs/Api/CoursesApi.md#listcourseevaluations) | **GET** /courses/{id}/evaluations | List evaluations for a course.
*CoursesApi* | [**listCourseGroups**](docs/Api/CoursesApi.md#listcoursegroups) | **GET** /courses/{id}/groups | List groups for a course.
*CoursesApi* | [**listCourseStudents**](docs/Api/CoursesApi.md#listcoursestudents) | **GET** /courses/{id}/students | List students attending a course.
*DegreesApi* | [**getDegreeById**](docs/Api/DegreesApi.md#getdegreebyid) | **GET** /degrees/{id} | Get degree by ID.
*DegreesApi* | [**listDegreeCourses**](docs/Api/DegreesApi.md#listdegreecourses) | **GET** /degrees/{id}/courses | List courses for a degree.
*DegreesApi* | [**listDegrees**](docs/Api/DegreesApi.md#listdegrees) | **GET** /degrees | List degrees.
*DomainModelApi* | [**getDomainModel**](docs/Api/DomainModelApi.md#getdomainmodel) | **GET** /domainModel | Get the application&#39;s domain model (DML) representation.
*PeoplePrivateApi* | [**getPerson**](docs/Api/PeoplePrivateApi.md#getperson) | **GET** /person | Get the current person&#39;s information.
*PeoplePrivateApi* | [**getPersonCalendarClasses**](docs/Api/PeoplePrivateApi.md#getpersoncalendarclasses) | **GET** /person/calendar/classes | Get the user&#39;s class calendar.
*PeoplePrivateApi* | [**getPersonCalendarEvaluations**](docs/Api/PeoplePrivateApi.md#getpersoncalendarevaluations) | **GET** /person/calendar/evaluations | Get the user&#39;s evaluation calendar.
*PeoplePrivateApi* | [**getPersonCourses**](docs/Api/PeoplePrivateApi.md#getpersoncourses) | **GET** /person/courses | Get the user&#39;s courses.
*PeoplePrivateApi* | [**getPersonCurriculum**](docs/Api/PeoplePrivateApi.md#getpersoncurriculum) | **GET** /person/curriculum | Get the user&#39;s complete curriculum (students only).
*PeoplePrivateApi* | [**getPersonEvaluations**](docs/Api/PeoplePrivateApi.md#getpersonevaluations) | **GET** /person/evaluations | Get the user&#39;s written evaluations.
*PeoplePrivateApi* | [**getPersonPayments**](docs/Api/PeoplePrivateApi.md#getpersonpayments) | **GET** /person/payments | Get the user&#39;s payments information.
*PeoplePrivateApi* | [**updatePersonEvaluationEnrolment**](docs/Api/PeoplePrivateApi.md#updatepersonevaluationenrolment) | **PUT** /person/evaluations/{id} | Enrol or disenrol from a written evaluation by ID.
*SpacesApi* | [**getSpaceBlueprint**](docs/Api/SpacesApi.md#getspaceblueprint) | **GET** /spaces/{id}/blueprint | Get a space&#39;s blueprint.
*SpacesApi* | [**getSpaceById**](docs/Api/SpacesApi.md#getspacebyid) | **GET** /spaces/{id} | Get a space by ID (campus, building, floor, or room).
*SpacesApi* | [**listSpaces**](docs/Api/SpacesApi.md#listspaces) | **GET** /spaces | List campi.

## Models

- [AboutResponse](docs/Model/AboutResponse.md)
- [AboutResponseRss](docs/Model/AboutResponseRss.md)
- [BibliographicReference](docs/Model/BibliographicReference.md)
- [CalendarClassEvent](docs/Model/CalendarClassEvent.md)
- [CalendarCourseRef](docs/Model/CalendarCourseRef.md)
- [CalendarEvaluationEvent](docs/Model/CalendarEvaluationEvent.md)
- [CanteenDay](docs/Model/CanteenDay.md)
- [CanteenItem](docs/Model/CanteenItem.md)
- [CanteenMeal](docs/Model/CanteenMeal.md)
- [Contact](docs/Model/Contact.md)
- [Course](docs/Model/Course.md)
- [CourseCompetence](docs/Model/CourseCompetence.md)
- [CourseLoad](docs/Model/CourseLoad.md)
- [CourseSchedule](docs/Model/CourseSchedule.md)
- [CourseStudents](docs/Model/CourseStudents.md)
- [CourseStudentsStudentsInner](docs/Model/CourseStudentsStudentsInner.md)
- [CourseTeacher](docs/Model/CourseTeacher.md)
- [CurriculumApprovedCourse](docs/Model/CurriculumApprovedCourse.md)
- [DateRange](docs/Model/DateRange.md)
- [Degree](docs/Model/Degree.md)
- [DegreeCampusInner](docs/Model/DegreeCampusInner.md)
- [DegreeCourse](docs/Model/DegreeCourse.md)
- [DegreeInfo](docs/Model/DegreeInfo.md)
- [DegreeRef](docs/Model/DegreeRef.md)
- [DmlClass](docs/Model/DmlClass.md)
- [DmlClassSlot](docs/Model/DmlClassSlot.md)
- [DmlRelation](docs/Model/DmlRelation.md)
- [DmlRelationRole](docs/Model/DmlRelationRole.md)
- [DomainModelResponse](docs/Model/DomainModelResponse.md)
- [Evaluation](docs/Model/Evaluation.md)
- [EvaluationCoursesInner](docs/Model/EvaluationCoursesInner.md)
- [GetPersonCalendarClasses200Response](docs/Model/GetPersonCalendarClasses200Response.md)
- [GetPersonCalendarEvaluations200Response](docs/Model/GetPersonCalendarEvaluations200Response.md)
- [Group](docs/Model/Group.md)
- [GroupAssociatedCoursesInner](docs/Model/GroupAssociatedCoursesInner.md)
- [Lesson](docs/Model/Lesson.md)
- [ParkingSite](docs/Model/ParkingSite.md)
- [PaymentsResponse](docs/Model/PaymentsResponse.md)
- [PaymentsResponseCompletedInner](docs/Model/PaymentsResponseCompletedInner.md)
- [PaymentsResponsePendingInner](docs/Model/PaymentsResponsePendingInner.md)
- [Person](docs/Model/Person.md)
- [PersonCoursesResponse](docs/Model/PersonCoursesResponse.md)
- [PersonCoursesResponseEnrolmentsInner](docs/Model/PersonCoursesResponseEnrolmentsInner.md)
- [PersonCurriculumItem](docs/Model/PersonCurriculumItem.md)
- [PersonPhoto](docs/Model/PersonPhoto.md)
- [PersonRoleAlumni](docs/Model/PersonRoleAlumni.md)
- [PersonRoleStudent](docs/Model/PersonRoleStudent.md)
- [PersonRoleStudentRegistrationsInner](docs/Model/PersonRoleStudentRegistrationsInner.md)
- [PersonRoleTeacher](docs/Model/PersonRoleTeacher.md)
- [PersonRoleTeacherDepartment](docs/Model/PersonRoleTeacherDepartment.md)
- [PersonRolesInner](docs/Model/PersonRolesInner.md)
- [RoomRef](docs/Model/RoomRef.md)
- [RoomRefCapacity](docs/Model/RoomRefCapacity.md)
- [RssFeed](docs/Model/RssFeed.md)
- [Shift](docs/Model/Shift.md)
- [ShiftOccupation](docs/Model/ShiftOccupation.md)
- [ShuttleDate](docs/Model/ShuttleDate.md)
- [ShuttleResponse](docs/Model/ShuttleResponse.md)
- [ShuttleStation](docs/Model/ShuttleStation.md)
- [ShuttleTrip](docs/Model/ShuttleTrip.md)
- [ShuttleTripStop](docs/Model/ShuttleTripStop.md)
- [SpaceDetail](docs/Model/SpaceDetail.md)
- [SpaceDetailCapacity](docs/Model/SpaceDetailCapacity.md)
- [SpaceDetailEventsInner](docs/Model/SpaceDetailEventsInner.md)
- [SpaceDetailParentSpace](docs/Model/SpaceDetailParentSpace.md)
- [SpaceSummary](docs/Model/SpaceSummary.md)
- [TopLevelSpaceRef](docs/Model/TopLevelSpaceRef.md)

## Authorization

Authentication schemes defined for the API:
### bearerAuth

- **Type**: Bearer authentication (JWT)

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `1.2.0`
    - Generator version: `7.15.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`

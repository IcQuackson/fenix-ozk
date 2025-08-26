<?php
namespace App\Contracts;

interface FenixPort
{
    // --- About & Metadata ---
    public function getAbout(?string $lang = null): array;
    public function listAcademicTerms(?string $lang = null): array;
    public function getDomainModel(?string $lang = null): array;

    // --- Contacts & Facilities ---
    public function getCanteenMenu(?string $lang = null): array;
    public function getContacts(?string $lang = null): array;
    public function getParking(?string $lang = null): array;
    public function getShuttle(?string $lang = null): array;

    // --- Courses ---
    public function getCourseById(string $id, ?string $lang = null): array;
    public function listCourseEvaluations(string $id, ?string $lang = null): array;
    public function listCourseGroups(string $id, ?string $lang = null): array;
    public function getCourseSchedule(string $id, ?string $lang = null): array;
    public function listCourseStudents(string $id, ?string $lang = null): array;

    // --- Degrees ---
    public function listDegrees(?string $term = null, ?string $lang = null): array;
    public function getDegreeById(string $id, ?string $term = null, ?string $lang = null): array;
    public function listDegreeCourses(string $id, ?string $term = null, ?string $lang = null): array;

    // --- Spaces ---
    public function listSpaces(?string $lang = null): array;
    public function getSpaceById(string $id, ?string $day = null, ?string $lang = null): array;
    public function getSpaceBlueprint(string $id, string $format, ?string $lang = null): mixed;

    // --- People (Private, require token) ---
    public function getPerson(int $userId, ?string $lang = null): array;
    public function getPersonCalendarClasses(int $userId, ?string $format = 'json', ?string $lang = null): array|string;
    public function getPersonCalendarEvaluations(int $userId, ?string $format = 'json', ?string $lang = null): array|string;
    public function getPersonCourses(int $userId, ?string $term = null, ?string $lang = null): array;
    public function getPersonCurriculum(int $userId, ?string $lang = null): array;
    public function getPersonEvaluations(int $userId, ?string $lang = null): array;
    public function updatePersonEvaluationEnrolment(int $userId, string $id, bool $enrol, ?string $lang = null): array;
    public function getPersonPayments(int $userId, ?string $lang = null): array;
}

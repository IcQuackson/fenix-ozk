<?php
namespace App\ViewModels;

use App\Domain\Entities\CourseEvaluation;

final class NextEvaluationsVM
{
    /** @param CourseEvaluation[] $evaluations */
    public function __construct(public array $evaluations)
    {
    }

    public static function fromDomain(array $evaluations): self
    {
        return new self($evaluations);
    }

    /** @return array<int,array{course:string,name:string,exam_at:?string}> */
    public function toArray(): array
    {
        return array_map(fn(CourseEvaluation $e) => [
            'course' => $e->course->name,
            'name' => $e->name,
            'exam_at' => sprintf(
                '%s %s - %s',
                $e->evaluationPeriod->start->format('d/m/Y'),
                $e->evaluationPeriod->start->format('H:i'),
                $e->evaluationPeriod->end->format('H:i')
            ),
            'room' => $e->assignedRoom?->name,
        ], $this->evaluations);
    }
}

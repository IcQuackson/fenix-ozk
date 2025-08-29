<?php
namespace App\ViewModels;

use App\Domain\Entities\Course;
use App\Domain\Entities\CourseAnnouncement;

final class CurrentCoursesAnnouncementsVM
{
	/**
	 * @var array<array{
	 *     announcements: CourseAnnouncement[]
	 * }>
	 */
	private array $announcements;

	/**
	 * @param array{announcements: CourseAnnouncement[]} $items
	 */
	public function __construct(array $items)
	{
		foreach ($items as $item) {
			if (!$item instanceof CourseAnnouncement) {
				throw new \InvalidArgumentException(
					sprintf('Expected instance of %s, got %s', CourseAnnouncement::class, get_debug_type($a))
				);
			}
		}

		$this->announcements = $items;
	}

	/**
	 * Factory from domain objects
	 *
	 * @param array<array{announcements: CourseAnnouncement[]}> $items
	 */
	public static function fromDomain(array $items): self
	{
		return new self($items);
	}

	public function toArray(): array
	{
		return array_map(fn(CourseAnnouncement $a) => [
			'courseName' => $a->courseName,
			'title' => $a->title,
			'link' => $a->link,
			'description' => $a->description,
			'author' => $a->author,
			'category' => $a->category,
			'publishedAt' => $a->publishedAt->format('d/m/Y H:i'),
		], $this->announcements);
	}
}

<?php

namespace App\Domain\Entities;

use Carbon\CarbonImmutable;

final class CourseAnnouncement
{
    public function __construct(
        public string $courseId,
        public string $courseName,
        public string $title,
        public string $link,
        public string $description,
        public string $author,
        public string $guid,
        public string $category,
        public CarbonImmutable $publishedAt,
    ) {
    }

    public static function fromRssItem(\SimpleXMLElement $item): self
    {
        $rawDescription = (string) $item->description;

        // Decode HTML entities into real tags
        $decoded = html_entity_decode($rawDescription, ENT_QUOTES | ENT_HTML5);

        // Allowed tags we want to keep
        $allowed = '<p><br><ul><ol><li><strong><b><em><i><a>';

        // Strip everything else, but keep allowed
        $clean = strip_tags($decoded, $allowed);

        // Fix links to always open safely
        $clean = preg_replace(
            '/<a(.*?)href="(.*?)"(.*?)>/i',
            '<a$1href="$2"$3 target="_blank" rel="noopener">',
            $clean
        );

        $clean = trim($clean);

        return new self(
            courseId: '0',
            courseName: '',
            title: (string) $item->title,
            link: (string) $item->link,
            description: $clean,
            author: (string) $item->author,
            guid: (string) $item->guid,
            category: (string) $item->category,
            publishedAt: new CarbonImmutable((string) $item->pubDate),
        );
    }

}

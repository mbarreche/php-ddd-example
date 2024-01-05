<?php

declare(strict_types = 1);

namespace CodelyTv\Mooc\Videos\Domain;

use CodelyTv\Mooc\Shared\Domain\Courses\CourseId;
use CodelyTv\Mooc\Shared\Domain\Videos\VideoUrl;
use CodelyTv\Shared\Domain\Aggregate\AggregateRoot;
use CodelyTv\Shared\Domain\ValueObject\Uuid;

final class Video extends AggregateRoot
{
    private $id;
    private $type;
    private $title;
    private $url;
    private $courseId;

    public function __construct(VideoId $id, VideoType $type, VideoTitle $title, VideoUrl $url, CourseId $courseId)
    {
        $this->id       = $id;
        $this->type     = $type;
        $this->title    = $title;
        $this->url      = $url;
        $this->courseId = $courseId;
    }

    public static function create(
        VideoType $type,
        VideoTitle $title,
        VideoUrl $url,
        CourseId $courseId
    ): Video {
        $videoId = new VideoId(Uuid::random()->value());
        $video = new self($videoId, $type, $title, $url, $courseId);

        $video->record(
            new VideoCreatedDomainEvent(
                $videoId->value(),
                [
                    'type'     => $type->value(),
                    'title'    => $title->value(),
                    'url'      => $url->value(),
                    'courseId' => $courseId->value(),
                ]
            )
        );

        return $video;
    }

    public function updateTitle(VideoTitle $newTitle): void
    {
        $this->title = $newTitle;
    }

    public function id(): VideoId
    {
        return $this->id;
    }

    public function type(): VideoType
    {
        return $this->type;
    }

    public function title(): VideoTitle
    {
        return $this->title;
    }

    public function url(): VideoUrl
    {
        return $this->url;
    }

    public function courseId(): CourseId
    {
        return $this->courseId;
    }
}

<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Embeddable()
 */
final class Attachment implements JsonSerializable
{
    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $contentId;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $contentType;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $contentDisposition;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $filename;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $content;

    public function __construct(
        ?string $contentId,
        string $contentType,
        string $contentDisposition,
        string $filename,
        string $content,
    ) {
        $this->contentId = $contentId ?? (string) Uuid::v4();
        $this->contentType = $contentType;
        $this->contentDisposition = $contentDisposition;
        $this->filename = $filename;
        $this->content = $content;
    }

    public function getContentId(): string
    {
        return $this->contentId;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getContentDisposition(): string
    {
        return $this->contentDisposition;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function jsonSerialize()
    {
        return [
            'contentId' => $this->contentId,
            'contentType' => $this->contentType,
            'contentDisposition' => $this->contentDisposition,
            'filename' => $this->filename,
            'content' => $this->content,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use App\Core\Domain\Entity\Attachment;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @GQL\Type(name="Attachment")
 */
class AttachmentGraphQLType
{
    private Attachment $attachment;

    public function __construct(
        Attachment $attachment
    ) {
        $this->attachment = $attachment;
    }

    /**
     * @GQL\Field(type="String", name="contentId")
     */
    public function getContentId(): string
    {
        return $this->attachment->getContentId();
    }

    /**
     * @GQL\Field(type="String", name="contentType")
     */
    public function getContentType(): string
    {
        return $this->attachment->getContentType();
    }

    /**
     * @GQL\Field(type="String", name="contentDisposition")
     */
    public function getContentDisposition(): string
    {
        return $this->attachment->getContentDisposition();
    }

    /**
     * @GQL\Field(type="String", name="filename")
     */
    public function getFilename(): string
    {
        return $this->attachment->getFilename();
    }

    /**
     * @GQL\Field(type="String", name="content")
     */
    public function getContent(): string
    {
        return $this->attachment->getContent();
    }
}

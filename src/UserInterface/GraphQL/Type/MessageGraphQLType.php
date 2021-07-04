<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use App\Core\Domain\Entity\Address;
use App\Core\Domain\Entity\Attachment;
use App\Core\Domain\Entity\Header;
use App\Core\Domain\Entity\Message;
use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * @GQL\Type(name="Message")
 */
class MessageGraphQLType
{
    private Message $message;

    public function __construct(
        Message $message
    ) {
        $this->message = $message;
    }

    /**
     * @GQL\Field(type="String", name="id")
     */
    public function getId(): string
    {
        return $this->message->getId()->jsonSerialize();
    }

    /**
     * @GQL\Field(type="String", name="date")
     */
    public function getDate(): string
    {
        return $this->message->getDate()->format('c');
    }

    /**
     * @GQL\Field(type="String", name="subject")
     */
    public function getSubject(): string
    {
        return $this->message->getSubject();
    }

    /**
     * @GQL\Field(type="Address", name="from")
     */
    public function getFrom(): Address
    {
        return $this->message->getFrom();
    }

    /**
     * @GQL\Field(type="[Address]", name="recipients")
     *
     * @return array<int, AddressGraphQLType>
     */
    public function getToRecipients(): array
    {
        return array_map(
            fn (Address $address) => new AddressGraphQLType($address),
            $this->message->getToRecipients()
        );
    }

    /**
     * @GQL\Field(type="[Attachment]", name="attachments")
     *
     * @return array<int, AttachmentGraphQLType>
     */
    public function getAttachments(): array
    {
        return array_map(
            fn (Attachment $attachment) => new AttachmentGraphQLType($attachment),
            $this->message->getAttachments()
        );
    }

    /**
     * @GQL\Field(type="Boolean", name="hasInlinedAttachments")
     */
    public function hasInlinedAttachments(): bool
    {
        return !empty(array_filter(
            $this->message->getAttachments(),
            fn (Attachment $attachment) => 'inline' === $attachment->getContentDisposition()
        ));
    }

    /**
     * @GQL\Field(type="Boolean", name="hasDownloadableAttachments")
     */
    public function hasDowloadableAttachments(): bool
    {
        return !empty(array_filter(
            $this->message->getAttachments(),
            fn (Attachment $attachment) => 'attachment' === $attachment->getContentDisposition()
        ));
    }

    /**
     * @GQL\Field(type="String", name="group")
     */
    public function getGroup(): ?string
    {
        return $this->message->getMailGroup();
    }

    /**
     * @GQL\Field(type="[Header]", name="headers")
     *
     * @return array<int,HeaderGraphQLType>
     */
    public function getHeaders(): array
    {
        return array_map(
            fn (Header $header) => new HeaderGraphQLType($header->getKey(), $header->getValue()),
            $this->message->getHeaders()
        );
    }

    /**
     * @GQL\Field(type="String", name="text")
     */
    public function getTextContent(): string
    {
        return $this->message->getTextContent();
    }

    /**
     * @GQL\Field(type="String", name="html")
     */
    public function getHtmlContent(): string
    {
        return $this->message->getHtmlContent() ?: $this->message->getTextContent();
    }
}

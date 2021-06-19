<?php

declare(strict_types=1);

namespace App\UserInterface\GraphQL\Type;

use App\Core\Domain\Entity\Address;
use App\Core\Domain\Entity\Attachment;
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
     * @GQL\Field(type="String", name="body")
     */
    public function getTextContent(): string
    {
        return $this->message->getTextContent();
    }
}

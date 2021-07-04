<?php

declare(strict_types=1);

namespace App\Core\Domain\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity
 */
final class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private UuidV4 $id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private DateTimeImmutable $date;

    /**
     * @ORM\Column(type="address", name="name", length=100, nullable=false)
     */
    private Address $from;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private ?string $subject = '';

    /**
     * @ORM\Column(type="addresses" )
     *
     * @var array<int, Address>
     */
    private array $toRecipients;

    /**
     * @ORM\Column(type="text" )
     */
    private string $textContent = '';

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private ?string $htmlContent = '';

    /**
     * @ORM\Column(type="text" )
     */
    private string $rawContent = '';

    /**
     * @var Header[]
     * @ORM\Column(type="headers" )
     * */
    private array $headers;

    /**
     * @ORM\Column(type="addresses" )
     *
     * @var array<int, Address>
     */
    private array $ccRecipients;

    /**
     * @ORM\Column(type="addresses" )
     *
     * @var array<int, Address>
     */
    private array $bccRecipients;

    /**
     * @ORM\Column(type="attachments" )
     *
     * @var array<int, Attachment>
     */
    private array $attachments;

    /**
     * @ORM\Column(type="boolean" )
     */
    private bool $read;

    /**
     * @ORM\Column(type="string" , nullable=false)
     */
    private string $mailGroup;

    /**
     * @param array<int, Address>    $toRecipients
     * @param array<int, Address>    $ccRecipients
     * @param array<int, Address>    $bccRecipients
     * @param array<int, Header>     $headers
     * @param array<int, Attachment> $attachments
     */
    public function __construct(
        DateTimeImmutable $date,
        Address $from,
        string $subject,
        array $toRecipients,
        array $ccRecipients,
        array $bccRecipients,
        array $headers,
        array $attachments,
        string $textContent,
        ?string $htmlContent,
        string $rawContent,
        string $mailGroup,
    ) {
        $this->id = Uuid::v4();
        $this->date = $date;
        $this->from = $from;
        $this->subject = $subject;
        $this->toRecipients = $toRecipients;
        $this->ccRecipients = $ccRecipients;
        $this->bccRecipients = $bccRecipients;
        $this->headers = $headers;
        $this->attachments = $attachments;
        $this->textContent = $textContent;
        $this->htmlContent = $htmlContent;
        $this->rawContent = $rawContent;
        $this->read = false;
        $this->mailGroup = $mailGroup;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getTextContent(): string
    {
        return $this->textContent;
    }

    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    /** @return Header[] */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getFrom(): Address
    {
        return $this->from;
    }

    /** @return Address[] */
    public function getToRecipients(): array
    {
        return $this->toRecipients;
    }

    /** @return Address[] */
    public function getCcRecipients(): array
    {
        return $this->ccRecipients;
    }

    /** @return Address[] */
    public function getBccRecipients(): array
    {
        return $this->bccRecipients;
    }

    public function getRawContent(): string
    {
        return $this->rawContent;
    }

    /** @return Attachment[] */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function read(): void
    {
        $this->read = true;
    }

    public function unread(): void
    {
        $this->read = false;
    }

    public function getMailGroup(): string
    {
        return $this->mailGroup;
    }
}

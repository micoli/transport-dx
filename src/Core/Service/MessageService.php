<?php

declare(strict_types=1);

namespace App\Core\Service;

use App\Core\Domain\Entity\Address;
use App\Core\Domain\Entity\Attachment;
use App\Core\Domain\Entity\Header;
use App\Core\Domain\Entity\Message;
use DateTimeImmutable;
use ZBateson\MailMimeParser\Header\AbstractHeader;
use ZBateson\MailMimeParser\Header\AddressHeader;
use ZBateson\MailMimeParser\Header\HeaderConsts;
use ZBateson\MailMimeParser\Header\Part\AddressPart;
use ZBateson\MailMimeParser\MailMimeParser;
use ZBateson\MailMimeParser\Message as MailMimeParserMessage;
use ZBateson\MailMimeParser\Message\Part\MessagePart;

final class MessageService
{
    private string $groupHeaderName;

    public function __construct(string $groupHeaderName)
    {
        $this->groupHeaderName = $groupHeaderName;
    }

    public function createFromSmtpMessage(string $mailBody): Message
    {
        $mailParser = new MailMimeParser();
        $parsedEmail = $mailParser->parse($mailBody);

        return new Message(
            new DateTimeImmutable($parsedEmail->getHeader(HeaderConsts::DATE)->getValue() ?? ''),
            $this->getFrom($parsedEmail),
            $this->getSubject($parsedEmail),
            $this->getToRecipients($parsedEmail),
            $this->getCcRecipients($parsedEmail),
            $this->getBccRecipients($parsedEmail),
            $this->getHeaders($parsedEmail),
            $this->getAttachments($parsedEmail),
            $parsedEmail->getTextContent() ?: '',
            $parsedEmail->getHtmlContent(),
            $mailBody,
            $this->getGroup($parsedEmail)
        );
    }

    private function getFrom(MailMimeParserMessage $parsedEmail): Address
    {
        /** @var AddressHeader $addressHeader */
        $addressHeader = $parsedEmail->getHeader(HeaderConsts::FROM);

        return new Address(
            $addressHeader->getValue(),
            $addressHeader->getPersonName()
        );
    }

    private function getSubject(MailMimeParserMessage $parsedEmail): string
    {
        return $parsedEmail->getHeader(HeaderConsts::SUBJECT)->getValue();
    }

    /**
     * @return array<int, Address>
     */
    private function getToRecipients(MailMimeParserMessage $parsedEmail): array
    {
        /** @var ?AddressHeader $addressHeader */
        $addressHeader = $parsedEmail->getHeader(HeaderConsts::TO);

        return array_map(fn (AddressPart $address) => new Address(
            $address->getEmail(),
            $address->getName()
        ), $addressHeader?->getAddresses() ?? []);
    }

    /**
     * @return array<int, Address>
     */
    private function getCcRecipients(MailMimeParserMessage $parsedEmail): array
    {
        /** @var ?AddressHeader $addressHeader */
        $addressHeader = $parsedEmail->getHeader(HeaderConsts::CC);

        return array_map(fn (AddressPart $address) => new Address(
            $address->getEmail(),
            $address->getName()
        ), $addressHeader?->getAddresses() ?? []);
    }

    /**
     * @return array<int, Address>
     */
    private function getBccRecipients(MailMimeParserMessage $parsedEmail): array
    {
        /** @var ?AddressHeader $addressHeader */
        $addressHeader = $parsedEmail->getHeader(HeaderConsts::BCC);

        return array_map(fn (AddressPart $address) => new Address(
            $address->getEmail(),
            $address->getName()
        ), $addressHeader?->getAddresses() ?? []);
    }

    /**
     * @return array<int, Header>
     */
    private function getHeaders(MailMimeParserMessage $parsedEmail, ): array
    {
        return array_map(fn (AbstractHeader $header) => new Header(
            $header->getName(),
            $header->getValue()
        ), $parsedEmail->getAllHeaders() ?? []);
    }

    private function getGroup(MailMimeParserMessage $parsedEmail, ): string
    {
        /** @var AbstractHeader[] $groupHeaders */
        $groupHeaders = array_filter(
            $parsedEmail->getAllHeaders() ?? [],
            fn (AbstractHeader $header) => $header->getName() === $this->groupHeaderName
        );
        if (empty($groupHeaders)) {
            return '';
        }

        return array_values($groupHeaders)[0]->getValue();
    }

    /**
     * @return array<int, Attachment>
     */
    private function getAttachments(MailMimeParserMessage $parsedEmail): array
    {
        return array_map(fn (MessagePart $attachment) => new Attachment(
            $attachment->getContentId(),
            $attachment->getContentType(),
            $attachment->getContentDisposition(),
            $attachment->getFilename(),
            $attachment->getContent(),
        ), $parsedEmail->getAllAttachmentParts());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Core\Service;

use App\Core\Service\MessageService;
use App\Tests\AbstractIntegrationTest;
use Symfony\Component\Uid\Uuid;

class MessageServiceTest extends AbstractIntegrationTest
{
    /**
     * @var MessageService
     */
    private $messageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->messageService = $this->getService(MessageService::class);
    }

    /**
     * @test
     */
    public function itShouldCreateFromSmtpMessage()
    {
        $message = $this->messageService->createFromSmtpMessage(
            $this->getRawMessageBody()
        );
        $this->assertInstanceOf(Uuid::class, $message->getId());
        $this->assertSame('2021-06-27T15:40:37+02:00', $message->getDate()->format('c'));
        $this->assertSame('john@doe.com', $message->getFrom()->getAddress());
        $this->assertSame('John Doe', $message->getFrom()->getDisplay());
        $this->assertCount(2, $message->getToRecipients());
        $this->assertSame('receiver@domain.org', $message->getToRecipients()[0]->getAddress());
        $this->assertSame('', $message->getToRecipients()[0]->getDisplay());
        $this->assertSame('other@domain.org', $message->getToRecipients()[1]->getAddress());
        $this->assertSame('A name', $message->getToRecipients()[1]->getDisplay());
        $this->assertSame('Here is the message itself'.PHP_EOL, $message->getTextContent());
        $this->assertSame('RunClient.php', $message->getAttachments()[0]->getFilename());
    }

    protected function getRawMessageBody(): string
    {
        return <<<EOS
            Message-ID: <67c9f2b966fa95d357424bf87d78bd8c@swift.generated>
            Date: Sun, 27 Jun 2021 15:40:37 +0200
            Subject: Wonderful Subject
            From: John Doe <john@doe.com>
            To: receiver@domain.org, A name <other@domain.org>
            MIME-Version: 1.0
            Content-Type: multipart/mixed;
             boundary="_=_swift_1624801237_b8b4e9324faec702ed314c6502974361_=_"
            
            
            --_=_swift_1624801237_b8b4e9324faec702ed314c6502974361_=_
            Content-Type: text/plain; charset=utf-8
            Content-Transfer-Encoding: quoted-printable
            
            Here is the message itself
            
            --_=_swift_1624801237_b8b4e9324faec702ed314c6502974361_=_
            Content-Type: application/x-php; name=RunClient.php
            Content-Transfer-Encoding: base64
            Content-Disposition: attachment; filename=RunClient.php
            
            IyEvdXNyL2Jpbi9lbnYgcGhwCjw/cGhwCgppbmNsdWRlIF9fRElSX18uJy8uLi8uLi92ZW5kb3Iv
            YXV0b2xvYWQucGhwJzsKCmZ1bmN0aW9uIHRlc3RNYWlsKHN0cmluZyAkYXV0aGVudGljYXRpb25N
            ZXRob2QsID9zdHJpbmcgJHVzZXIsID9zdHJpbmcgJHBhc3N3b3JkKTogdm9pZAp7CiAgICAkdHJh
            bnNwb3J0ID0gKG5ldyBTd2lmdF9TbXRwVHJhbnNwb3J0KCcxMjcuMC4wLjEnLCA4MDI1KSk7Cgog
            ICAgaWYgKCdOT05FJyAhPT0gJGF1dGhlbnRpY2F0aW9uTWV0aG9kKSB7CiAgICAgICAgJHRyYW5z
            cG9ydAogICAgICAgICAgICAtPnNldEF1dGhNb2RlKCRhdXRoZW50aWNhdGlvbk1ldGhvZCkKICAg
            ICAgICAgICAgLT5zZXRVc2VybmFtZSgkdXNlcikKICAgICAgICAgICAgLT5zZXRQYXNzd29yZCgk
            cGFzc3dvcmQpOwogICAgfQoKICAgICRtZXNzYWdlID0gKG5ldyBTd2lmdF9NZXNzYWdlKCdXb25k
            ZXJmdWwgU3ViamVjdCcpKQogICAgICAgIC0+c2V0RnJvbShbJ2pvaG5AZG9lLmNvbScgPT4gJ0pv
            aG4gRG9lJ10pCiAgICAgICAgLT5zZXRUbyhbJ3JlY2VpdmVyQGRvbWFpbi5vcmcnLCAnb3RoZXJA
            ZG9tYWluLm9yZycgPT4gJ0EgbmFtZSddKQogICAgICAgIC0+c2V0Qm9keSgnSGVyZSBpcyB0aGUg
            bWVzc2FnZSBpdHNlbGYnKQogICAgICAgIC0+YXR0YWNoKFN3aWZ0X0F0dGFjaG1lbnQ6OmZyb21Q
            YXRoKF9fRklMRV9fKSk7CgogICAgJG1haWxlciA9IG5ldyBTd2lmdF9NYWlsZXIoJHRyYW5zcG9y
            dCk7CiAgICAkcmVzdWx0ID0gJG1haWxlci0+c2VuZCgkbWVzc2FnZSk7CiAgICBlY2hvIHNwcmlu
            dGYoCiAgICAgICAgIlNlbmQgbWFpbCB1c2luZyAlcyBhdXRoZW50aWNhdGlvbiwgbnVtYmVyIG9m
            IHJlY2lwaWVudHMgOiAlZFxuIiwKICAgICAgICAkYXV0aGVudGljYXRpb25NZXRob2QsCiAgICAg
            ICAgJHJlc3VsdAogICAgKTsKfQoKdGVzdE1haWwoJ0xPR0lOJywgJ3VzZXIxJywgJ3Bhc3N3b3Jk
            MScpOwp0ZXN0TWFpbCgnUExBSU4nLCAndXNlcjEnLCAncGFzc3dvcmQxJyk7CnRlc3RNYWlsKCdD
            UkFNLU1ENScsICd1c2VyMScsICdwYXNzd29yZDEnKTsKLy90ZXN0TWFpbCgnTk9ORScsIG51bGws
            IG51bGwpOwo=
            
            --_=_swift_1624801237_b8b4e9324faec702ed314c6502974361_=_--
            
            EOS;
    }
}

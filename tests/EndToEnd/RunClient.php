#!/usr/bin/env php
<?php

include __DIR__.'/../../vendor/autoload.php';

function testMail(string $authenticationMethod, ?string $user, ?string $password): void
{
    $transport = (new Swift_SmtpTransport('127.0.0.1', 8025));

    if ('NONE' !== $authenticationMethod) {
        $transport
            ->setAuthMode($authenticationMethod)
            ->setUsername($user)
            ->setPassword($password);
    }

    $message = (new Swift_Message('Wonderful Subject'))
        ->setFrom(['john@doe.com' => 'John Doe'])
        ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
        ->setBody('Here is the message itself')
        ->attach(Swift_Attachment::fromPath(__FILE__));

    $mailer = new Swift_Mailer($transport);
    $result = $mailer->send($message);
    echo sprintf(
        "Send mail using %s authentication, number of recipients : %d\n",
        $authenticationMethod,
        $result
    );
}

testMail('LOGIN', 'user1', 'password1');
testMail('PLAIN', 'user1', 'password1');
testMail('CRAM-MD5', 'user1', 'password1');
//testMail('NONE', null, null);

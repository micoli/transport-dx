#!/usr/bin/env php
<?php

include __DIR__.'/../../vendor/autoload.php';

function randomGroup(Swift_Message $message): void
{
    $groups = [null, 'group 1', 'group B', 'test 123 456', 'other group'];
    $group = $groups[array_rand($groups)];
    if (null !== $group) {
        $message->getHeaders()->addTextHeader('x-mail-group', $group);
    }
}

function sendMail(string $authenticationMethod, ?string $user, ?string $password): void
{
    $faker = Faker\Factory::create();

    $transport = (new Swift_SmtpTransport('127.0.0.1', 8025));

    $transport
        ->setAuthMode($authenticationMethod)
        ->setUsername($user)
        ->setPassword($password);

    $message = (new Swift_Message($faker->words(random_int(1, 10), true)))
        ->setFrom([$faker->email => $faker->name()])
        ->setTo([$faker->email => $faker->name(), $faker->email])
        ->setBody($faker->text(random_int(10, 2000)));
    randomGroup($message);

    if (1 === random_int(0, 3)) {
        $message->setBody(
            '<html>'.
            ' <body>'.
            $faker->text(random_int(10, 2000)).
            ' <img src="'.
            $message->embed(Swift_Image::fromPath(__DIR__.'/'.random_int(1, 3).'.jpg')).
            '" alt="Image" />'.
            $faker->text(random_int(10, 2000)).
            ' </body>'.
            '</html>',
            'text/html' // Mark the content-type as HTML
        );
    }
    if (1 === random_int(0, 1)) {
        $message->attach(Swift_Attachment::fromPath(__FILE__));
    }

    $mailer = new Swift_Mailer($transport);
    $result = $mailer->send($message);
    echo sprintf(
        "Number of recipients : %d\n",
        $result
    );
}

for ($number = 0; $number < 10; ++$number) {
    sendMail('LOGIN', 'user1', 'password1');
}

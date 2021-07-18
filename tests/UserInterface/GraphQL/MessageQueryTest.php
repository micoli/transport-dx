<?php

declare(strict_types=1);

namespace App\Tests\UserInterface\GraphQL;

use App\Tests\AbstractIntegrationTest;

final class MessageQueryTest extends AbstractIntegrationTest
{
    /**
     * @test
     */
    public function itShouldGetAllMessages(): void
    {
        $graphqlResult = $this->graphqlRequest(<<<EOG
          query Messages{
                messages {
                  date
                  id
                  subject
                  from {
                    address
                    display
                  }
                  recipients {
                    address
                    display
                  }
                  group
                }
              }
        EOG
        );
        self::assertCount(4, $graphqlResult['data']['messages']);
        self::assertSame(
            [
                'subject 2',
                'subject 1',
                'subject 3',
                'subject 4',
            ],
            array_map(fn (array $message) => $message['subject'], $graphqlResult['data']['messages'])
        );
    }

    /**
     * @test
     */
    public function itShouldGetAllByGroup(): void
    {
        $graphqlResult = $this->graphqlRequest(<<<EOG
          query Messages{
                messages(groupName:"group1") {
                  date
                  subject
                  group
                }
              }
        EOG
        );
        self::assertCount(2, $graphqlResult['data']['messages']);
    }

    /**
     * @test
     */
    public function itShouldGetAllByNoGroup(): void
    {
        $graphqlResult = $this->graphqlRequest(<<<EOG
          query Messages{
                messages(groupName: "") {
                  date
                  subject
                  group
                }
              }
        EOG
        );
        self::assertCount(1, $graphqlResult['data']['messages']);
    }
}

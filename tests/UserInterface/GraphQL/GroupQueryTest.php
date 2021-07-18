<?php

declare(strict_types=1);

namespace App\Tests\UserInterface\GraphQL;

use App\Tests\AbstractIntegrationTest;

final class GroupQueryTest extends AbstractIntegrationTest
{
    /**
     * @test
     */
    public function itShouldGetAllGroups(): void
    {
        $graphqlResult = $this->graphqlRequest(<<<EOG
            query groups {
                groups {
                    name
                    numberOfMessage
                }
            }
        EOG
        );
        self::assertCount(3, $graphqlResult['data']['groups']);
        self::assertSame([
                [
                    'name' => '',
                    'numberOfMessage' => 1,
                ],
                ['name' => 'group1',
                    'numberOfMessage' => 2,
                ],
                [
                    'name' => 'group2',
                    'numberOfMessage' => 1,
                ],
            ],
            $graphqlResult['data']['groups']
        );
    }
}

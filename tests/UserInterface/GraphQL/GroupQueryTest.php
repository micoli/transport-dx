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

    /**
     * @test
     */
    public function itShouldGetAllGroupsWithUnreadCount(): void
    {
        $graphqlResult = $this->graphqlRequest(<<<EOG
            query groups {
                groups {
                    name
                }
            }
        EOG
        );
        $groupName = $graphqlResult['data']['groups'][1]['name'];
        $messagesGraphqlResult = $this->graphqlRequest(<<<EOG
            query {
                messages(groupName: "$groupName" ) {
                    date
                    id
                    isRead
                    subject
                    group
                    from {
                        address
                    }
                    hasDownloadableAttachments
                    hasInlinedAttachments
                }
            }
        EOG
        );
        $firstId = $messagesGraphqlResult['data']['messages'][0]['id'];
        $this->graphqlRequest(<<<EOG
            mutation {
                changeReadStatus(messageId: "$firstId",isRead: true)
            }
        EOG
        );
        $graphqlResult = $this->graphqlRequest(<<<EOG
            query groups {
                groups {
                    name
                    numberOfMessage
                    numberOfUnreadMessage
                }
            }
        EOG
        );
        self::assertCount(3, $graphqlResult['data']['groups']);
        self::assertSame([
                [
                    'name' => '',
                    'numberOfMessage' => 1,
                    'numberOfUnreadMessage' => 1,
                ],
                ['name' => 'group1',
                    'numberOfMessage' => 2,
                    'numberOfUnreadMessage' => 1,
                ],
                [
                    'name' => 'group2',
                    'numberOfMessage' => 1,
                    'numberOfUnreadMessage' => 1,
                ],
            ],
            $graphqlResult['data']['groups']
        );
    }
}

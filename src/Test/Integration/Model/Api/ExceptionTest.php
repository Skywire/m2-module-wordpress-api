<?php
/**
 * Copyright © Skywire Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Skywire\WordpressApi\Model\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Skywire\WordpressApi\Test\Integration\Model\Api\AbstractApiTest;

/**+
 * @package     Skywire\WordpressApi\Model\Api
 * @author      Skywire Tech Team <tech@skywire.co.uk>
 */
class ExceptionTest extends AbstractApiTest
{
    public function testException()
    {
        $mock = new MockHandler([
            new Response(401, [], 'Unauthorised'),
        ]);

        $handler    = HandlerStack::create($mock);
        $restClient = new Client(['handler' => $handler]);

        $category = $this->objectManager->create(Category::class,
            ['restClient' => $restClient]);


        $this->expectException(ApiException::class);

        $category->getCollection();
    }
}
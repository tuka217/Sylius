<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Tests\Controller;

use Lakion\ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class BookApiTest extends JsonApiTestCase
{
    /**
     * @test
     */
    public function shouldCreateAProduct()
    {
        $data =
            <<<EOT
        {
            "title": "Star Wars"
        }
EOT;
        $this->client->request('POST', '/books/', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'books/create_response', Response::HTTP_CREATED);
    }
}

<?php

namespace App\Tests\Integration;

use Symfony\Component\HttpFoundation\Response;

class ProductTest extends AbstractTestCase
{
    /**
     * Last inserted Product Id
     *
     * @var null|string
     */
    protected static $lastInsertedProductId;

    /**
     * Get last inserted Product ID
     *
     * @return null|string
     */
    public static function getLastInsertedProductId()
    {
        return self::$lastInsertedProductId;
    }

    protected static function clearLastInsertedProductId()
    {
        self::$lastInsertedProductId = null;
    }

    /**
     * Get Valid Data for Theme POST Request
     *
     * @param array $replaceData
     *
     * @return array
     * @throws \Exception
     */
    public static function getValidDataForProduct(array $replaceData = [])
    {
        $faker = \Faker\Factory::create();
        $baseData = [
            'title' => $faker->word(),
            'price' => $faker->randomFloat(),
            'eid' => $faker->randomNumber(),
        ];

        return array_merge($baseData, $replaceData);
    }

    /**
     * API create Product
     *
     * @param array $data
     *
     * @return null|Response
     */
    protected static function createProduct(array $data)
    {
        $client = AbstractTestCase::getClient();

        $client->request(
            'POST',
            '/api/products',
            $data
        );
        $response = $client->getResponse();

        if ($response->getStatusCode() === Response::HTTP_CREATED) {
            $json = self::getResponseArray($response);
            self::$lastInsertedProductId = $json['result']['id'];
        } else {
            self::$lastInsertedProductId = false;
        }

        return $response;
    }

    /**
     * API get Product
     *
     * @param string $id
     *
     * @return null|Response
     */
    protected static function getProduct($id)
    {
        $client = AbstractTestCase::getClient();
        $client->request(
            'GET',
            '/api/products/' . $id
        );
        return $client->getResponse();
    }

    /**
     * Test success POST action
     * @group product
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function testPost()
    {
        self::clearDb();

        // WHEN
        $requestData = self::getValidDataForProduct();
        $response = self::createProduct($requestData);

        // THEN
        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $jsonResponse = self::getResponseArray($response);
        self::assertSame(['result'], array_keys($jsonResponse));
        $resultData = $jsonResponse['result'];
        unset($resultData['id']);
        unset($resultData['categoryIds']);

        self::assertSame($requestData, $resultData);
    }

    /**
     * Test success GET action
     * @group theme
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function testGet()
    {
        self::clearDb();

        // GIVEN
        $requestData = self::getValidDataForProduct();
        self::createProduct($requestData);
        $id = self::getLastInsertedProductId();
        self::assertIsInt($id);
        // WHEN
        $response = self::getProduct($id);

        // THEN
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        $jsonResponse = self::getResponseArray($response);
        $resultData = $jsonResponse['result'];
        unset($resultData['id']);
        unset($resultData['categoryIds']);

        self::assertSame($requestData, $resultData);
    }
}
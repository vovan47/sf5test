<?php

namespace  App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractTestCase extends WebTestCase
{
    private static $dbInitClear = false;

    private static $client = null;

    /**
     * Clear DB
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected static function clearDb()
    {
        `php bin/console doctrine:database:drop --env=test --force`;
        `php bin/console doctrine:database:create --env=test`;
        `php bin/console doctrine:schema:update --env=test --force`;
    }

    /**
     * @throws \Doctrine\ORM\OrmException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        if (!self::$dbInitClear) {
            self::clearDb();
            self::$dbInitClear = true;
        }
    }

    /**
     * Get decoded JSON from the Response
     *
     * @param Response $response
     *
     * @return mixed
     */
    public static function getResponseArray(Response $response)
    {
        return json_decode($response->getContent(), true);
    }

    /**
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    public static function getKernel()
    {
        return self::$kernel;
    }

    /**
     * @param array $options
     * @param array $server
     * @return \Symfony\Bundle\FrameworkBundle\KernelBrowser|\Symfony\Component\BrowserKit\AbstractBrowser|null
     */
    public static function getClient(array $options = [], array $server = [])
    {
        if (self::$client == null) {
            self::$client = self::createClient($options, $server);
        }
        return self::$client;
    }
}
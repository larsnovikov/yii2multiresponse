<?php
/**
 * Created by PhpStorm.
 * User: novikov
 * Date: 24.05.18
 * Time: 15:25
 */

namespace vendor\larsnovikov\yii2multiresponse\storages;

use Ratchet\ConnectionInterface;

/**
 * Class BaseStorage
 * @package vendor\larsnovikov\yii2multiresponse\storages
 */
class BaseStorage implements StorageInterface
{
    private const TOKEN_LIFE_TIME = 60;

    /**
     * Зарегистрированные токены
     * @var array
     */
    private static $registeredTokens = [];

    /**
     * Ответы
     * @var array
     */
    private static $registeredResponses = [];

    /**
     * Время регистрации токенов
     * @var array
     */
    private static $registerTimes = [];

    /**
     * Регистрация токена
     * token => Client
     * @param string $token
     * @param ConnectionInterface $client
     */
    public static function registerToken(string $token, ConnectionInterface $client): void
    {
        self::saveRegisterTime($token);

        self::$registeredTokens[$token] = $client;
    }

    /**
     * Регистрация ответа
     * token => response
     * @param string $token
     * @param string $response
     */
    public static function registerResponse(string $token, string $response): void
    {
        self::saveRegisterTime($token);

        self::$registeredResponses[$token] = $response;
    }

    /**
     * Получение объекта клиента по токену
     * @param string $token
     * @param bool $once
     * @return null|ConnectionInterface
     */
    public static function getClientByToken(string $token, bool $once = true): ?ConnectionInterface
    {
        if (array_key_exists($token, self::$registeredTokens)) {
            $client = self::$registeredTokens[$token];
            unset(self::$registeredTokens[$token]);
            return $client;
        }

        return null;
    }

    /**
     * Получение ответа по токену
     * @param string $token
     * @param bool $once
     * @return null|string
     */
    public static function getResponseByToken(string $token, bool $once = true): ?string
    {
        if (array_key_exists($token, self::$registeredResponses)) {
            $response = self::$registeredResponses[$token];
            unset(self::$registeredResponses[$token]);
            return $response;
        }

        return null;
    }

    /**
     * Сохранение времени регистрации токена
     *
     * @param string $token
     */
    private static function saveRegisterTime(string $token): void
    {
        $time = time();
        if (!array_key_exists($time, self::$registerTimes)) {
            self::$registerTimes[$time] = [];
        }

        self::$registerTimes[$time][] = $token;

        self::collectGarbage();
    }

    /**
     * Очистка старых невостребованных токенов и клиентов
     */
    private static function collectGarbage(): void
    {
        $deadline = time() - self::TOKEN_LIFE_TIME;

        foreach (self::$registerTimes as $token => &$time) {
            if ($deadline > $time) {
                break;
            }
            if (array_key_exists($token, self::$registeredResponses)) {
                unset(self::$registeredResponses[$token]);
            }
            if (array_key_exists($token, self::$registeredTokens)) {
                unset(self::$registeredTokens[$token]);
            }

            unset(self::$registerTimes[$time]);
        }
    }
}

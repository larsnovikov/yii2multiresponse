<?php
/**
 * Created by PhpStorm.
 * User: novikov
 * Date: 24.05.18
 * Time: 15:26
 */

namespace vendor\larsnovikov\yii2multiresponse\storages;

use Ratchet\ConnectionInterface;

/**
 * Interface StorageInterface
 * @package vendor\larsnovikov\yii2multiresponse\storages
 */
interface StorageInterface
{
    /**
     * Регистрация токена
     * token => Client
     * @param string $token
     * @param ConnectionInterface $client
     */
    public static function registerToken(string $token, ConnectionInterface $client): void;

    /**
     * Регистрация ответа
     * token => response
     * @param string $token
     * @param string $response
     */
    public static function registerResponse(string $token, string $response): void;

    /**
     * Получение объекта клиента по токену
     * @param string $token
     * @param bool $once
     * @return null|ConnectionInterface
     */
    public static function getClientByToken(string $token, bool $once = true): ?ConnectionInterface;

    /**
     * Получение ответа по токену
     * @param string $token
     * @param bool $once
     * @return null|string
     */
    public static function getResponseByToken(string $token, bool $once = true): ?string;
}

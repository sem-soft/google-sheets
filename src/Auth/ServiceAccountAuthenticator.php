<?php
/**
 * Файл класса ServiceAccountAuthenticator.php
 *
 * @copyright Copyright (c) 2019, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace Sem\GoogleSheets\Auth;

use Google\Auth\CredentialsLoader;
use Sem\GoogleSheets\Exceptions\CredentialPathException;

/**
 * Организует аутентификацию клиента через сервисный аккаунт
 * @package Sem\GoogleSheets\Client
 */
class ServiceAccountAuthenticator
{
    /**
     * Устанавливает переменные окружения для аутентификации клиента через сервисный аккаунт
     * и сообщает об этом объекту клиента
     *
     * @param \Google_Client $client клиент для конфигурации
     * @param string $credentialsPath
     * @return \Google_Client
     * @throws CredentialPathException
     */
    public function prepareClient(\Google_Client $client, $credentialsPath): \Google_Client
    {

        if (!file_exists($credentialsPath)) {
            throw new CredentialPathException("Wrong credential file path. File does not exists");
        }

        // Установка параметра GOOGLE_APPLICATION_CREDENTIALS для авторизации
        putenv(CredentialsLoader::ENV_VAR . "={$credentialsPath}");
        $client->useApplicationDefaultCredentials();
        return $client;
    }
}
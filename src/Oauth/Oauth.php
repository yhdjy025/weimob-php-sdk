<?php
/**
 * Created by yhdjy.
 * Email: chenweiE@sailvan.com
 * Date: 2018/6/21
 * Time: 14:55
 */

namespace Weimob\Oauth;

use Exception;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class Oauth
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * oauth
     * @param array $config
     * @return array
     * @throws Exception
     */
    public function auth($config = [])
    {
        $provider = new GenericProvider($this->config);

// If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            header('Location: ' . $authorizationUrl);
            exit;

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

            if (isset($_SESSION['oauth2state'])) {
                unset($_SESSION['oauth2state']);
            }
            throw new Exception('Invalid state', -1);

        } else {

            try {

                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                return [
                    'accessToken'  => $accessToken->getToken(),
                    'refreshToken' => $accessToken->getRefreshToken(),
                    'expires'      => $accessToken->getExpires(),
                ];

            } catch (IdentityProviderException $e) {

                // Failed to get the access token or user details.
                throw new Exception($e->getMessage(), -1);

            }
        }
    }

    /**
     * refresh access token
     * @param $refreshToken
     * @return array
     */
    public function refreshAccessToken($refreshToken)
    {
        $provider = new GenericProvider($this->config);

        $accessToken = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $refreshToken
        ]);

        return [
            'accessToken'  => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'expires'      => $accessToken->getExpires(),
        ];
    }
}
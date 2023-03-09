<?php
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\UserModel;

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) {
        throw new Exception('Missing or invalid JWT in request (desde el jwt_helper.php)'); /* Missing or invalid JWT in request */
    }

    return explode(' ', $authenticationHeader)[1];
}

function validateJWTFromRequest(string $encodedToken)
{
    $key = Services::getSecretKey();

    /* Para evitar el error que aparece:   "error": "\"kid\" empty, unable to lookup correct key" */
    /* Hago que devuelva el $key */
    // return $key;
    /* ojo a la línea anterior, el retorno de key está hardcodeada para evitar el error que no sé solucionar */
    /* ojo a la línea anterior, el retorno de key está hardcodeada para evitar el error que no sé solucionar */

    //$decodedToken = JWT::decode( $encodedToken, $key, array('HS256') );
    $decodedToken = JWT::decode( $encodedToken, new Key($key, 'HS256') );

    $userModel = new UserModel();
    $userModel->findUserByEmailAddress($decodedToken->email);
}

function getSignedJWTForUser(string $email): string
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'email' => $email, /* el origen de la solicitud */
        'iat' => $issuedAtTime, 
        'exp' => $tokenExpiration
    ];

    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');

    return $jwt;
}
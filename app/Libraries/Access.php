<?php

namespace App\Libraries;

use Exception;
use \Firebase\JWT\JWT;

class Access
{
    private $token;
    private $data;
    private $jwtPrivateKey;
    private $jwtPublicKey;
    private $jwtAlg = 'RS256';

    const DENIED = ['message' => 'Access Denied'];
    const EXPIRED = ['message' => 'Token Expired'];
    const INVALID = ['message' => 'Token Invalid'];

    public function __construct()
    {
        $this->token = request()->bearerToken();
        if (empty($this->token)) $this->token = request()->input('Authorization');

        $this->jwtPrivateKey = file_get_contents(__DIR__ . '/../jwt/private.key');
        $this->jwtPublicKey = file_get_contents(__DIR__ . '/../jwt/public.key');
    }

    public function createToken($data)
    {
        return JWT::encode($data, $this->jwtPrivateKey, $this->jwtAlg);
    }

    public function isLogin()
    {
        if (!$this->validateToken()) return false;
        return true;
    }

    public function is_user($value)
    {
        if (!$this->validateToken()) return false;
        if (in_array($value, $this->getData('id_user_group'))) return true;

        return false;
    }

    public function in_user($value)
    {
        if (!$this->validateToken()) return false;

        $explode_user = explode('|', $value);
        foreach ($explode_user as $key => $value) $explode_user[$key] = strtoupper(trim($value));
        if (in_array($this->getData('id_user_group'), $explode_user)) return true;

        return false;
    }

    public function getData($value = NULL)
    {
        if (!$this->validateToken()) return NULL;
        if ($value == NULL) return $this->data;
        return array_key_exists($value, $this->data) ? $this->data[$value] : NULL;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getError()
    {
        return $this->error;
    }

    private function validateToken()
    {
        if (!empty($this->data)) return true;

        $this->error = self::DENIED;
        if (empty($this->token)) return false;

        try
        {
            $data = JWT::decode($this->token, $this->jwtPublicKey, array($this->jwtAlg));
            $this->data = json_decode(json_encode($data), true);
        }
        catch (\Firebase\JWT\ExpiredException $e)
        {
            $this->error = self::EXPIRED;
            return false;
        }
        catch (Exception $e)
        {
            $this->error = self::INVALID;
            return false;
        }

        return true;
    }
}

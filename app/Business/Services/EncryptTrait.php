<?php

namespace App\Business\Services;

use App\Utilities\Log;

/**
 * Class ProductOptionMappingTrait
 *
 * 暗号化トレイト
 *
 * @package App\Business\UseCases\Admin
 */
trait EncryptTrait
{
    /**
     * 暗号化
     *
     * @param $data
     * @param $password
     * @return string
     */
    public function encrypt($data, $password)
    {
        //ログ出力
        Log::infoIn();

        // Set a random salt
        $salt = openssl_random_pseudo_bytes(16);

        $salted = '';
        $dx = '';
        // Salt the key(32) and iv(16) = 48
        while (strlen($salted) < 48) {
            $dx = hash('sha256', $dx . $password . $salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        $encrypted_data = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);

        //ログ出力
        Log::infoOut();

        return base64_encode($salt . $encrypted_data);
    }

    /**
     * 複合化
     *
     * @param $edata
     * @param $password
     * @return string
     */
    public function decrypt($edata, $password)
    {
        //ログ出力
        Log::infoIn();

        $data = base64_decode($edata);
        $salt = substr($data, 0, 16);
        $ct = substr($data, 16);

        $rounds = 3; // depends on key length
        $data00 = $password . $salt;
        $hash = array();
        $hash[0] = hash('sha256', $data00, true);
        $result = $hash[0];
        for ($i = 1; $i < $rounds; $i++) {
            $hash[$i] = hash('sha256', $hash[$i - 1] . $data00, true);
            $result .= $hash[$i];
        }
        $key = substr($result, 0, 32);
        $iv = substr($result, 32, 16);

        //ログ出力
        Log::infoOut();

        return openssl_decrypt($ct, 'AES-256-CBC', $key, 0, $iv);
    }
}
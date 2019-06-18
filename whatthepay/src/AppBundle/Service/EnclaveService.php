<?php


namespace AppBundle\Service;


class EnclaveService
{

    private $recogPath;
    public function __construct($recogPath)
    {
        $this->recogPath = $recogPath;
    }

    public function GenerateKey($token, $hash)
    {
        return hash_pbkdf2('sha256', $token, $hash, 10000, 32);
    }

    public function GeneratePhotoHash($path, $key)
    {
        exec($this->recogPath . '/venv/bin/python3 ' . $this->recogPath . '/addFace.py "' . $path . '" "' . $key .'"', $out, $var);

        if ($var !== 0) {
            throw new \InvalidArgumentException('Try again with a different image.');
        }
        return $out[0];
    }

    public function CheckMatch($hash, $path, $key)
    {
        exec($this->recogPath . '/venv/bin/python3 ' .
            $this->recogPath . '/recognizer.py ' .
            $path . ' "' .
            $key . '" "' . $hash . '"', $out, $var);

        dump($out);

        return $var === 0 && $out[0] === '0';
    }
}
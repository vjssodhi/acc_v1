<?php
namespace Application\Utilities;

use Zend\Json\Json;

class NumberPlay
{

    /**
     *
     * @param sting $number            
     * @param int $width            
     * @return string
     */
    public static function conver10ToBase36($number, $width = null)
    {
        if (is_string($number)) {
            if (is_int($width)) {
                return str_pad(gmp_strval(gmp_init($number, 10), 36), $width, "0", STR_PAD_LEFT);
            }
            return gmp_strval(gmp_init($number, 10), 36);
        }
    }

    public static function cleaner($data, $exclude = null)
    {
        foreach ($data as $key => $value) {
            if (! empty($value) && is_string($value) && (! isset($exclude[$key]))) {
                $data[$key] = preg_replace('!\s+!', ' ', trim($value));
            }
        }
        return $data;
    }

    public static function conver36ToBase10($numberString)
    {
        if (! is_string($numberString)) {
            $message = sprintf(
                'Please provide the number is string form.(Use strval() function to convert int to string)');
            throw new \InvalidArgumentException($message);
        }
        
        return gmp_strval(gmp_init($numberString, 36), 10);
    }

    public static function getASCIIPassword($length)
    {
        $printable = "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";
        return self::getCustomPassword(str_split($printable), $length);
    }

    public static function getAlphaNumericPassword($length)
    {
        $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        return self::getCustomPassword(str_split($alphanum), $length);
    }

    public static function getHexPassword($length)
    {
        $hex = "0123456789ABCDEF";
        return self::getCustomPassword(str_split($hex), $length);
    }

    public static function getCustomPassword($characterSet, $length)
    {
        if ($length < 1 || ! is_array($characterSet))
            return false;
        
        $charSetLen = count($characterSet);
        if ($charSetLen == 0)
            return false;
        
        $random = self::getRandomInts($length * 2);
        $mask = self::getMinimalBitMask($charSetLen - 1);
        
        $password = "";
        
        $iterLimit = max($length, $length * 64);
        $randIdx = 0;
        while (strlen($password) < $length) {
            if ($randIdx >= count($random)) {
                $random = self::getRandomInts(2 * ($length - strlen($password)));
                $randIdx = 0;
            }
            $c = $random[$randIdx ++] & $mask;
            if ($c < $charSetLen)
                $password .= self::sidechannel_safe_array_index($characterSet, $c);
            
            $iterLimit --;
            if ($iterLimit <= 0)
                return false;
        }
        
        return $password;
    }

    private static function sidechannel_safe_array_index($string, $index)
    {
        if (count($string) > 65535 || $index > count($string)) {
            return false;
        }
        $character = 0;
        for ($i = 0; $i < count($string); $i ++) {
            $x = $i ^ $index;
            $mask = (((($x | ($x >> 16)) & 0xFFFF) + 0xFFFF) >> 16) - 1;
            $character |= ord($string[$i]) & $mask;
        }
        return chr($character);
    }

    private static function getMinimalBitMask($toRepresent)
    {
        if ($toRepresent < 1)
            return false;
        $mask = 0x1;
        while ($mask < $toRepresent)
            $mask = ($mask << 1) | 1;
        return $mask;
    }

    public static function encfilecontents($contents, $encKey)
    {
        //
        $fileName = static::getAlphaNumericPassword(64);
        $required_file = ENC_CACHE_DIR . $fileName;
        // $handleZ = fopen($required_file, 'w');
        touch($required_file);
        $filter = new \Zend\Filter\Encrypt(array(
            'adapter' => 'BlockCipher'
        ));
        $filter->setKey($encKey);
        $encryptedText = $filter->filter(Json::encode($contents));
        file_put_contents($required_file, $encryptedText);
        // fclose($handleZ);
        return $required_file;
    }

    public static function decryptfilecontents($contents, $encKey)
    {
        $filter = new \Zend\Filter\Decrypt();
        $filter->setKey($encKey);
        $decoded = $filter->filter($contents);
        return Json::decode($decoded, JSON::TYPE_ARRAY);
    }

    public static function getRandomInts($numInts)
    {
        $ints = array();
        if ($numInts <= 0) {
            return $ints;
        }
        $rawBinary = mcrypt_create_iv($numInts * PHP_INT_SIZE, MCRYPT_DEV_URANDOM);
        for ($i = 0; $i < $numInts; ++ $i) {
            $thisInt = 0;
            for ($j = 0; $j < PHP_INT_SIZE; ++ $j) {
                $thisInt = ($thisInt << 8) | (ord($rawBinary[$i * PHP_INT_SIZE + $j]) & 0xFF);
            }
            $thisInt = $thisInt & PHP_INT_MAX;
            $ints[] = $thisInt;
        }
        return $ints;
    }

    public static function benchmark()
    {
        $timeTarget = 0.2;
        $cost = PASSWORD_COST_DEFAULT;
        do {
            $cost ++;
            $start = microtime(true);
            password_hash("test", PASSWORD_DEFAULT, [
                "cost" => $cost
            ]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);
        
        return $cost;
    }

    private static $encMap = array(
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 'A',
        11 => 'B',
        12 => 'C',
        13 => 'D',
        14 => 'E',
        15 => 'F',
        16 => 'G',
        17 => 'H',
        18 => 'I',
        19 => 'J',
        20 => 'K',
        21 => 'L',
        22 => 'M',
        23 => 'N',
        24 => 'O',
        25 => 'P',
        26 => 'Q',
        27 => 'R',
        28 => 'S',
        29 => 'T',
        30 => 'U',
        31 => 'V',
        32 => 'W',
        33 => 'X',
        34 => 'Y',
        35 => 'Z'
    );

    private static $decodeMap = array(
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        'A' => 10,
        'B' => 11,
        'C' => 12,
        'D' => 13,
        'E' => 14,
        'F' => 15,
        'G' => 16,
        'H' => 17,
        'I' => 18,
        'J' => 19,
        'K' => 20,
        'L' => 21,
        'M' => 22,
        'N' => 23,
        'O' => 24,
        'P' => 25,
        'Q' => 26,
        'R' => 27,
        'S' => 28,
        'T' => 29,
        'U' => 30,
        'V' => 31,
        'W' => 32,
        'X' => 33,
        'Y' => 34,
        'Z' => 35
    );

    private static $charList36 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
}
?>
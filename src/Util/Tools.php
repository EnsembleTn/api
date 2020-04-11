<?php

namespace App\Util;

use Exception;

/**
 * Class Tools
 *
 * A tool box
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Tools
{
    /**
     * Generate base_64 encoded token
     *
     * @return string
     * @throws Exception
     */
    public static function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * Generate cryptographically secure pseudo-random integers (concatenated to a prefix if provided)
     *
     * @param string $prefix
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function generateGUID(string $prefix = '', int $length = 10): string
    {
        $min = (int)str_pad('', $length, '1', STR_PAD_LEFT);
        $max = (int)str_pad('', $length, '9', STR_PAD_LEFT);

        return $prefix . '-' . random_int($min, $max);
    }

    /**
     * Verify that all required keys exist in array
     *
     * @param array $keys
     * @param array $arr
     * @return bool
     */
    public static function all_array_keys_exists(array $keys, array $arr): bool
    {
        return !array_diff_key(array_flip($keys), $arr);
    }

    /**
     * Return array second level keys
     *
     * @param $array
     * @return array
     */
    public static function array_second_level_keys($array)
    {
        $result = array();
        foreach($array as $sub) {
            $result = array_merge($result, $sub);
        }
        return array_keys($result);
    }


    /**
     * Generating a random password
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomPassword(int $length = 8): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);

        return $password;
    }

    /**
     * Generating a random 6-digit code
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomCode(int $length = 6): string
    {
        $chars = "123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

    /**
     * Check if a needle exits in the haystack string
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function contains(string $haystack, string $needle): bool
    {
        if (strpos($haystack, $needle) !== false)
            return true;
        return false;
    }

    /**
     * Slugify ugly strings
     *
     * @param string $string
     * @return string
     */
    public static function slugify(string $string): string
    {
        // replace non letter or digits by -
        $slug = preg_replace('~[^\pL\d]+~u', '.', $string);

        // normalize the string
        $slug = self::normalize($slug);


        // transliterate
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

        // remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '.', $slug);

        // trim
        $slug = trim($slug, '.');

        // remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);

        // lowercase
        $slug = strtolower($slug);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }

    /**
     * Normalize a string (removing accents)
     *
     * @param $string
     * @return string
     */
    private static function normalize($string)
    {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
        );

        return strtr($string, $table);
    }

    public static function csv_to_array($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Split full name into first name and last name
     *
     * @param string $fullName
     * @return array
     */
    public static function split_name(string $fullName): array
    {
        $fullName = trim($fullName);
        $last_name = (strpos($fullName, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $fullName);
        $first_name = trim(preg_replace('#' . $last_name . '#', '', $fullName));

        return ['firstName' => $first_name, 'lastName' => $last_name];
    }

    /**
     * Checking whether the Base64 encoded string is correct or not
     *
     * @param string $base64string
     *
     * @return bool
     */
    public static function isBase64String(?string $base64string): bool
    {
        return (base64_encode(base64_decode($base64string, true)) === $base64string);
    }

    /**
     * Tunisia 24 cities list
     *
     * @return array
     */
    public static function tunisiaCitiesList()
    {
        return [
            'ARIANA' => 'ARIANA',
            'BEJA' => 'BEJA',
            'BEN AROUS' => 'BEN AROUS',
            'BIZERTE' => 'BIZERTE',
            'GABES' => 'GABES',
            'GAFSA' => 'GAFSA',
            'JENDOUBA' => 'JENDOUBA',
            'KAIROUAN' => 'KAIROUAN',
            'KASSERINE' => 'KASSERINE',
            'KEBILI' => 'KEBILI',
            'KEF' => 'KEF',
            'MAHDIA' => 'MAHDIA',
            'MANOUBA' => 'MANOUBA',
            'MEDENINE' => 'MEDENINE',
            'MONASTIR' => 'MONASTIR',
            'NABEUL' => 'NABEUL',
            'SFAX' => 'SFAX',
            'SIDI BOUZID' => 'SIDI BOUZID',
            'SILIANA' => 'SILIANA',
            'SOUSSE' => 'SOUSSE',
            'TATAOUINE' => 'TATAOUINE',
            'TOZEUR' => 'TOZEUR',
            'TUNIS' => 'TUNIS',
            'ZAGHOUAN' => 'ZAGHOUAN',
        ];
    }
}

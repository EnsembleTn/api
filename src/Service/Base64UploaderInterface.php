<?php

namespace App\Service;

/**
 * Interface Base64UploaderInterface
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
interface Base64UploaderInterface
{
    /**
     * Handling file upload
     *
     * @param string $base64string
     * @param string $uploadPath
     *
     * @return string
     */
    function upload(string $base64string, string $uploadPath): string ;
}

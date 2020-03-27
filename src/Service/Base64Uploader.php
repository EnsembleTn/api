<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 * Class Base64Uploader
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class Base64Uploader implements Base64UploaderInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * Base64Uploader constructor.
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * Handling file upload
     *
     * @param string $base64string
     * @param string $uploadPath
     * @return string
     */
    public function upload(?string $base64string, string $uploadPath): string
    {
        // random name for file
        $fileName = $this->generateFileName();

        // construct file full path
        $filePath = "{$this->getUploadDir()}{$uploadPath}{$fileName}";

        // create directory if !exists
        $this->makeDir($filePath);

        // store file and return file name
        return $this->store($filePath, $base64string);
    }

    /**
     * Create and save file
     *
     * @param string $filePath
     * @param string $base64string
     * @return string
     */
    private function store(string $filePath, ?string $base64string): string
    {
        $file = fopen($filePath, 'wb');
        fwrite($file, base64_decode($base64string));
        fclose($file);

        $newFilePath = "{$filePath}.{$this->getFileExtension($filePath)}";
        rename($filePath, $newFilePath);

        return $newFilePath;
    }

    /**
     * get the public web directory
     *
     * @return string
     */
    private function getUploadDir(): string
    {
        return "{$this->parameterBag->get('kernel.project_dir')}/public/uploads/";
    }

    /**
     * Make the upload directory
     *
     * @param string $path
     */
    private function makeDir(string $path): void
    {
        $dirName = dirname($path);
        if (!is_dir($dirName)) {
            mkdir($dirName, 0755, true);
        }
    }

    /**
     * Generating a random file name
     *
     * @return string file name
     */
    private function generateFileName(): string
    {
        return sprintf('%s%s', uniqid(), time());
    }

    /**
     * Getting the extension of a file
     *
     * @param string $filePath
     *
     * @return string
     */
    private function getFileExtension(string $filePath)
    {
        $guesser = MimeTypeGuesser::getInstance();
        $extensionGuesser = new MimeTypeExtensionGuesser();

        return $extensionGuesser->guess(
            $guesser->guess($filePath)
        );
    }
}

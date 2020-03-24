<?php

namespace App\Entity;

use App\Entity\Traits\ObjectMetaDataTrait;
use App\Entity\Traits\SoftDeleteableTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Util\OctusTools;
use App\Util\Tools;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Class File
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class File
{
    // <editor-fold defaultstate="collapsed" desc="traits">

    use ObjectMetaDataTrait;
    use TimestampableTrait;
    use SoftDeleteableTrait;

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="attributes">

    /**
     * @var int The file Id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string The file global unique identifier
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $guid;

    /**
     * @var string The file name
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileName;

    /**
     * @var string The file path on the server
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filePath;

    /**
     * @var string The file related entity class name
     *
     * @ORM\Column(type="string", length=100)
     */
    private $entity;

    /**
     * @var int The related object Id
     *
     * @ORM\Column(type="integer")
     */
    private $objectId;

    /**
     * @var string The file mime type
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $mimeType;

    /**
     * @var string The file title
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $size;

    /**
     * @var string The file base64 encoded
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $base64encodedString;

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="relations">

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="methods">

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function getObjectId(): int
    {
        return $this->objectId;
    }

    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function getBase64EncodedString(): string
    {
        return $this->base64encodedString;
    }

    public function setBase64EncodedString(string $base64EncodedString)
    {
        $this->base64encodedString = $base64EncodedString;

        return $this;
    }

    /**
     * Make pre-persist operations
     *
     * @ORM\PrePersist
     * @throws Exception
     */
    public function prePersist()
    {
        $this->setGuid(Tools::generateGUID('FILE', 8));; // setting guid
    }
    // </editor-fold>
}

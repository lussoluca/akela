<?php

declare(strict_types=1);

namespace App\Entity\Core;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @Gedmo\Uploadable(path="uploads", filenameGenerator="ALPHANUMERIC", appendNumber=true)
 */
#[Entity]
#[Gedmo\Loggable]
class File implements FileInterface
{
    #[Column(name: 'id', type: 'integer')]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    /**
     * @Gedmo\UploadableFilePath
     */
    #[Column(name: 'path', type: 'string')]
    private string $path; // @phpstan-ignore-line

    /**
     * @Gedmo\UploadableFileName
     */
    #[Column(name: 'name', type: 'string')]
    private string $name; // @phpstan-ignore-line

    /**
     * @Gedmo\UploadableFileMimeType
     */
    #[Column(name: 'mime_type', type: 'string')]
    private string $mimeType; // @phpstan-ignore-line

    /**
     * @Gedmo\UploadableFileSize
     */
    #[Column(name: 'size', type: 'decimal')]
    private string $size; // @phpstan-ignore-line

    #[Column(name: 'persistent', type: 'boolean')]
    private bool $persistent = false;

    public function __construct() {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function isPersistent(): bool
    {
        return $this->persistent;
    }
}

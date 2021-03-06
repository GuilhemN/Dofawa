<?php

namespace XN\Metadata;

use Symfony\Component\Validator\Constraints as Assert;

trait FileTrait
{
    use FileLightTrait;

    /**
     * @Assert\Image(
     *     maxSize = "1024k",
     *     minWidth = 80,
     *     maxWidth = 135,
     *     minHeight = 80,
     *     maxHeight = 135,
     *     mimeTypesMessage = "Choisissez un fichier image valide.")
     */
    protected $file;
}

<?php
namespace XN\Metadata;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait FileTrait
{

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    protected $path;

    /**
    * @Assert\Image(
    *     maxSize = "1024k",
    *     minWidth = 80,
    *     maxWidth = 120,
    *     minHeight = 80,
    *     maxHeight = 120,
    *     mimeTypesMessage = "Choisissez un fichier image valide.")
    */
    private $file;

    public function getPath(){
        return $this->path;
    }

    public function setPath($path){
        $this->path = $path;
        return $this;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
        ? null
        : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
        ? null
        : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }

    /**
    * Sets file.
    *
    * @param UploadedFile $file
    */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
    * Get file.
    *
    * @return UploadedFile
    */
    public function getFile()
    {
        return $this->file;
    }

    public function generateFileName(){
        return time() . $this->getFile()->getClientOriginalName();
    }
}

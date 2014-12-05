<?php
namespace XN\Metadata;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileTrait
{

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    protected $path;

    /**
    * @Assert\Image(
    *     maxSize = "1024k",
    *     minWidth = 80,
    *     maxWidth = 80,
    *     minHeight = 80,
    *     maxHeight = 80,
    *     mimeTypesMessage = "Choisissez un fichier image valide.")
    */
    private $file;



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
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
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

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        if(!empty($this->path))
        $this->removeUpload();

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
        $this->getUploadRootDir(),
        $this->generateFileName()
        );

        // set the path property to the filename where you've saved the file
        $this->path = time().$this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    protected function generateFileName(){
        return time() . $this->getFile()->getClientOriginalName();
    }
    /**
    * @ORM\PreRemove()
    */
    public function removeUpload(){
        @unlink($this->getAbsolutePath());
    }

}

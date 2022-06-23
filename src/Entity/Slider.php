<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SliderRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: SliderRepository::class)]
class Slider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    /**
     * Undocumented variable
     *
     * @Vich\UploadableField(mapping= "slider_images", fileNameProperty= "image")
     */
    private ?File $imgFile = null;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $ordre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get the value of imgFile
     */ 
    public function getImgFile()
    {
        return $this->imgFile;
    }

    /**
     * Set the value of imgFile
     *
     * @return  self
     */ 
    public function setImgFile($imgFile)
    {
        $this->imgFile = $imgFile;

        return $this;
    }
}

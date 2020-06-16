<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\ReadOnly
     * @Serializer\Expose
     * @Serializer\Groups({"app-product-default"})
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="products")
     *
     * @Serializer\Expose
     * @Serializer\Groups({"app-product-default"})
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"app-product-default"})
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     *
     * @Serializer\Expose
     * @Serializer\Groups({"app-product-default"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"app-product-default"})
     */
    private $eid;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getEid(): ?int
    {
        return $this->eid;
    }

    public function setEid(?int $eid): self
    {
        $this->eid = $eid;

        return $this;
    }
}

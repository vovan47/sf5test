<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\VirtualProperty(
 *     "categoryIds",
 *     exp="object.getCategoryIds()",
 *     options={@Serializer\SerializedName("categories")}
 *  )
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
     * @Serializer\Groups({"app-product-extra"})
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(groups={"POST", "PATCH"})
     * @Assert\Length(min="3", max="12")
     *
     * @Serializer\Expose
     * @Serializer\Groups({"app-product-default"})
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     *
     * @Assert\Range(min="0", max="200")
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

    /**
     * @Serializer\ReadOnly
     * @Serializer\Expose
     * @Serializer\Accessor(getter="getCategoryIds")
     * @Serializer\Groups({"app-product-default"})
     */
    private $categoryIds;

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

    public function getCategoryIds(): array
    {
        $result = [];
        foreach ($this->categories as $category) {
            $result[] = $category->getId();
        }
        return $result;
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

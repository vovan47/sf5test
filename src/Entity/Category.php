<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 *
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\VirtualProperty(
 *     "productIds",
 *     exp="object.getProductyIds()",
 *     options={@Serializer\SerializedName("products")}
 *  )
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\ReadOnly
     * @Serializer\Expose
     * @Serializer\Groups({"app-category-default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"app-category-default"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @Serializer\Expose
     * @Serializer\Groups({"app-category-default"})
     */
    private $eid;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="categories")
     *
     * @Serializer\Groups({"app-category-extra"})
     */
    private $products;

    /**
     * @Serializer\ReadOnly
     * @Serializer\Expose
     * @Serializer\Accessor(getter="getProductIds")
     * @Serializer\Groups({"app-category-default"})
     */
    private $productIds;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEid(): ?int
    {
        return $this->eid;
    }

    public function setEid(?int $eid): self
    {
        $this->eid = $eid;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getProductIds(): array
    {
        $result = [];
        foreach ($this->products as $product) {
            $result[] = $product->getId();
        }
        return $result;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeCategory($this);
        }

        return $this;
    }
}

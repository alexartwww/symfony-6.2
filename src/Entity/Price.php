<?php

namespace Alexartwww\Symfony\Entity;

use Alexartwww\Symfony\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity(repositoryClass: PriceRepository::class)]
#[UniqueConstraint(name: "product_variant_currency", fields: ["product", "variant", "currency"])]
#[ORM\HasLifecycleCallbacks]
class Price
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        options: ["default" => "CURRENT_TIMESTAMP"]
    )]
    private ?\DateTime $created_at = null;

    #[ORM\Column(
        options: ["default" => "CURRENT_TIMESTAMP"]
    )]
    private ?\DateTime $updated_at = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'prices')]
    private Product $product;

    #[ORM\Column(length: 255)]
    private ?string $variant;

    #[ORM\Column(
        length: 255
    )]
    private ?string $currency;

    #[ORM\Column(
        type: "decimal",
        precision: 10,
        scale: 2
    )]
    private ?float $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getVariant(): ?string
    {
        return $this->variant;
    }

    public function setVariant(string $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}

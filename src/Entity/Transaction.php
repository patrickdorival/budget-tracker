<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Table(name="transactions")
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * @Groups({"transaction:list"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:list"})
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transaction:list"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionCategory::class, inversedBy="transactions")
     * @Groups({"transaction:list"})
     */
    private $transactionCategory;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @Groups({"transaction:list"})
     */
    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedOn(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTransactionCategory(): ?TransactionCategory
    {
        return $this->transactionCategory;
    }

    public function setTransactionCategory(?TransactionCategory $transactionCategory): self
    {
        $this->transactionCategory = $transactionCategory;

        return $this;
    }
}

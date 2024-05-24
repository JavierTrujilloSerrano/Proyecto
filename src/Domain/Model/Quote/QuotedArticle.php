<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Quote;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: QuotedArticleRepository::class)]
#[ORM\Table(name: "quoted_article")]
class QuotedArticle
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", nullable: false)]
    private Uuid $id;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'volume', type: "float", nullable: false)]
    private float $volumeInM3;

    #[ORM\Column(name: 'weight', type: "integer", nullable: false)]
    private int $weightInGrams;

    private function __construct(Uuid $id, string $name, float $volumeInM3, int $weightInGrams)
    {
        $this->id = $id;
        $this->name = $name;
        $this->volumeInM3 = $volumeInM3;
        $this->weightInGrams = $weightInGrams;
    }

    public static function createFromAllParams(Uuid $id, string $name, float $volumeInM3, int $weightInGrams): self
    {
        return new self($id, $name, $volumeInM3, $weightInGrams);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function volumeInM3(): float
    {
        return $this->volumeInM3;
    }

    public function setVolumeInM3(float $volumeInM3): self
    {
        $this->volumeInM3 = $volumeInM3;

        return $this;
    }

    public function weightInGrams(): int
    {
        return $this->weightInGrams;
    }

    public function setWeightInGrams(int $weightInGrams): self
    {
        $this->weightInGrams = $weightInGrams;

        return $this;
    }

}

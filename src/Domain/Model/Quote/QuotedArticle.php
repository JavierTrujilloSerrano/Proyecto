<?php
declare(strict_types=1);

namespace Proyecto\Domain\Model\Quote;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

//clase para crear la base de datos
#[ORM\Entity(repositoryClass: QuotedArticleRepository::class)]
#[ORM\Table(name: "quoted_article")]
class QuotedArticle implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", nullable: false)]
    private Uuid $id;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(name: 'volume', type: "float", nullable: false)]
    private float $volumeInCm3;

    #[ORM\Column(name: 'weight', type: "integer", nullable: false)]
    private int $weightInGrams;

    private function __construct(Uuid $id, string $name, float $volumeInCm3, int $weightInGrams)
    {
        $this->id = $id;
        $this->name = $name;
        $this->volumeInCm3 = $volumeInCm3;
        $this->weightInGrams = $weightInGrams;
    }

    public static function createFromAllParams(Uuid $id, string $name, float $volumeInCm3, int $weightInGrams): self
    {
        return new self($id, $name, $volumeInCm3, $weightInGrams);
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

    public function volumeInCm3(): float
    {
        return $this->volumeInCm3;
    }

    public function setVolumeInCm3(float $volumeInCm3): self
    {
        $this->volumeInCm3 = $volumeInCm3;

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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toRfc4122(),
            'name' => $this->name,
            'volumeInCm3' => $this->volumeInCm3(),
            'weightInGrams' => $this->weightInGrams(),
        ];
    }

}

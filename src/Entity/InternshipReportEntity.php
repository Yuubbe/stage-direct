<?php
// src/Entity/InternshipReportEntity.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tbl_internship_report')]
class InternshipReportEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'text')]
    private string $contenu;

    #[ORM\OneToOne(targetEntity: Internship::class, inversedBy: 'report')]
    private ?Internship $internship = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getInternship(): ?Internship
    {
        return $this->internship;
    }

    public function setInternship(?Internship $internship): self
    {
        $this->internship = $internship;
        return $this;
    }
}

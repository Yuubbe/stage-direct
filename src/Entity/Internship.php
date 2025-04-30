<?php

namespace App\Entity;

use App\Repository\InternshipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'tbl_internship')]
#[ORM\Entity(repositoryClass: InternshipRepository::class)]
class Internship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'internships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isPending = true; // Par défaut, un internship est en attente

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reportContent = null; // Contenu du rapport

    #[ORM\OneToMany(mappedBy: 'internship', targetEntity: InternshipReport::class, orphanRemoval: true)]
    private Collection $reports;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }


    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function isPending(): bool
    {
        return $this->isPending;
    }

    public function setPending(bool $isPending): static
    {
        $this->isPending = $isPending;

        return $this;
    }

    public function getReportContent(): ?string
    {
        return $this->reportContent;
    }

    public function setReportContent(?string $reportContent): static
    {
        $this->reportContent = $reportContent;

        return $this;
    }

    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(InternshipReport $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setInternship($this);
        }

        return $this;
    }

    public function removeReport(InternshipReport $report): self
    {
        if ($this->reports->removeElement($report)) {
            // Set the owning side to null (unless already changed)
            if ($report->getInternship() === $this) {
                $report->setInternship(null);
            }
        }

        return $this;
    }
}
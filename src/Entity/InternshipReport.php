<?php

namespace App\Entity;

use App\Repository\InternshipReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InternshipReportRepository::class)]
#[ORM\Table(name: 'tbl_internship_report')]
class InternshipReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Internship::class, inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Internship $internship = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $submissionDate = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title = null;

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubmissionDate(): ?\DateTimeInterface
    {
        return $this->submissionDate;
    }

    public function setSubmissionDate(\DateTimeInterface $submissionDate): self
    {
        $this->submissionDate = $submissionDate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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
}
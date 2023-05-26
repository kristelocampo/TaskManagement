<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\OneToMany(mappedBy: 'task_id', targetEntity: Comments::class)]
    private Collection $comment_id;


    #[ORM\ManyToOne(inversedBy: 'task_id')]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'task_id')]
    private ?Status $status_id = null;

    #[ORM\ManyToOne(inversedBy: 'task_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Priority $priority_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\ManyToOne(inversedBy: 'task_id')]
    private ?Projects $project_id = null;


    public function __construct()
    {
        $this->comment_id = new ArrayCollection();
        $this->task_id = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getCommentId(): Collection
    {
        return $this->comment_id;
    }

    public function addCommentId(Comments $commentId): self
    {
        if (!$this->comment_id->contains($commentId)) {
            $this->comment_id->add($commentId);
            $commentId->setTaskId($this);
        }

        return $this;
    }

    public function removeCommentId(Comments $commentId): self
    {
        if ($this->comment_id->removeElement($commentId)) {
            // set the owning side to null (unless already changed)
            if ($commentId->getTaskId() === $this) {
                $commentId->setTaskId(null);
            }
        }

        return $this;
    }


    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getStatusId(): ?Status
    {
        return $this->status_id;
    }

    public function setStatusId(?Status $status_id): self
    {
        $this->status_id = $status_id;

        return $this;
    }

    public function getPriorityId(): ?Priority
    {
        return $this->priority_id;
    }

    public function setPriorityId(?Priority $priority_id): self
    {
        $this->priority_id = $priority_id;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getProjectId(): ?Projects
    {
        return $this->project_id;
    }

    public function setProjectId(?Projects $project_id): self
    {
        $this->project_id = $project_id;

        return $this;
    }


}

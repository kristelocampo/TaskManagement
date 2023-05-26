<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'project_id')]
    private ?User $user_id = null;

    #[ORM\OneToMany(mappedBy: 'project_id', targetEntity: Tasks::class)]
    private Collection $task_id;

    public function __construct()
    {
        $this->task_id = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Tasks>
     */
    public function getTaskId(): Collection
    {
        return $this->task_id;
    }

    public function addTaskId(Tasks $taskId): self
    {
        if (!$this->task_id->contains($taskId)) {
            $this->task_id->add($taskId);
            $taskId->setProjectId($this);
        }

        return $this;
    }

    public function removeTaskId(Tasks $taskId): self
    {
        if ($this->task_id->removeElement($taskId)) {
            // set the owning side to null (unless already changed)
            if ($taskId->getProjectId() === $this) {
                $taskId->setProjectId(null);
            }
        }

        return $this;
    }


}

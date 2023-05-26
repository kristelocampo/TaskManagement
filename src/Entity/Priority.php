<?php

namespace App\Entity;

use App\Repository\PriorityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriorityRepository::class)]
class Priority
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'priority_id', targetEntity: Tasks::class)]
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
            $taskId->setPriorityId($this);
        }

        return $this;
    }

    public function removeTaskId(Tasks $taskId): self
    {
        if ($this->task_id->removeElement($taskId)) {
            // set the owning side to null (unless already changed)
            if ($taskId->getPriorityId() === $this) {
                $taskId->setPriorityId(null);
            }
        }

        return $this;
    }
}

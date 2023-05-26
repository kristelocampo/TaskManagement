<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'status_id', targetEntity: Tasks::class)]
    private Collection $task_id;

    public function __construct()
    {
        $this->task_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
            $taskId->setStatusId($this);
        }

        return $this;
    }

    public function removeTaskId(Tasks $taskId): self
    {
        if ($this->task_id->removeElement($taskId)) {
            // set the owning side to null (unless already changed)
            if ($taskId->getStatusId() === $this) {
                $taskId->setStatusId(null);
            }
        }

        return $this;
    }
}

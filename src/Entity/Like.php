<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?User $likedBy = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Article $likedPost = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    private ?Comment $likedComment = null;

    public function __toString(): string
    {
        return $this->likedBy . ' liked ' . $this->likedPost;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikedBy(): ?User
    {
        return $this->likedBy;
    }

    public function setLikedBy(?User $likedBy): self
    {
        $this->likedBy = $likedBy;

        return $this;
    }

    public function getLikedPost(): ?Article
    {
        return $this->likedPost;
    }

    public function setLikedPost(?Article $likedPost): self
    {
        $this->likedPost = $likedPost;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLikedComment(): ?Comment
    {
        return $this->likedComment;
    }

    public function setLikedComment(?Comment $likedComment): self
    {
        $this->likedComment = $likedComment;

        return $this;
    }
}

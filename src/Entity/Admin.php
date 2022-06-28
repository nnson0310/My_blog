<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @UniqueEntity("email", message="This email is already registered. If you own it, please try login first.")
 * @UniqueEntity("username", message="This username is already registered. If you own it, please try login first.")
 */
class Admin implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     * @Assert\NotBlank(message="Username can not null.")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=6, minMessage="Password must be at least {{ limit }} characters long")
     * @Assert\Regex(pattern="/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/", message="Password must contains at least one number and one character.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     * @Assert\NotBlank(message="Email can not null.")
     * @Assert\Email(message="Email is invalid.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer")
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $delete_flag;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $email_confirmation;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="created_by")
     */
    private $blogs;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="last_modified_by")
     */
    private $blogsByModify;

    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->blogsByModify = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }

    public function setRole(int $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getDeleteFlag(): ?int
    {
        return $this->delete_flag;
    }

    public function setDeleteFlag(?int $delete_flag): self
    {
        $this->delete_flag = $delete_flag;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return null;
    }

    public function getUserIdentifier()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getEmailConfirmation(): ?int
    {
        return $this->email_confirmation;
    }

    public function setEmailConfirmation(?int $email_confirmation): self
    {
        $this->email_confirmation = $email_confirmation;

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setCreatedBy($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getCreatedBy() === $this) {
                $blog->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogsByModify(): Collection
    {
        return $this->blogsByModify;
    }

    public function addBlogsByModify(Blog $blogsByModify): self
    {
        if (!$this->blogsByModify->contains($blogsByModify)) {
            $this->blogsByModify[] = $blogsByModify;
            $blogsByModify->setLastModifiedBy($this);
        }

        return $this;
    }

    public function removeBlogsByModify(Blog $blogsByModify): self
    {
        if ($this->blogsByModify->removeElement($blogsByModify)) {
            // set the owning side to null (unless already changed)
            if ($blogsByModify->getLastModifiedBy() === $this) {
                $blogsByModify->setLastModifiedBy(null);
            }
        }

        return $this;
    }

}

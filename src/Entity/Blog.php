<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 * @ORM\Table(name="blog", indexes={@ORM\Index(columns={"plain_content"}, flags={"fulltext"})})
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Topic::class, inversedBy="blogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topics;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Title must not be null")
     * @Assert\Length(min = 5, minMessage = "Title must be at least {{ limit }} characters in length.")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(maxSize= "2M", maxSizeMessage = "Image size is too large. Allowed maximum size is {{ limit }} {{ suffix }}")
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message = "Content must not be blank.")
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message = "Min read must not be blank.")
     * @Assert\Positive(message = "Min read must be greater than zero.")
     */
    private $min_read;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="blogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="blogsByModify")
     */
    private $last_modified_by;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="blogs")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Description can not be blank")
     * @Assert\Length(min=10, minMessage = "Description must be at least {{ limit }} characters in length.")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="blog", orphanRemoval=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="integer")
     */
    private $notification_email;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="blogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     */
    private $plain_content;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $total_views;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $today_views;

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

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comment = new ArrayCollection();
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

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

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

    public function getCreatedBy(): ?Admin
    {
        return $this->created_by;
    }

    public function setCreatedBy(?Admin $created_by): self
    {
        $this->created_by = $created_by;

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

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getTopics(): ?Topic
    {
        return $this->topics;
    }

    public function setTopics(?Topic $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    public function getLastModifiedBy(): ?Admin
    {
        return $this->last_modified_by;
    }

    public function setLastModifiedBy(?Admin $last_modified_by): self
    {
        $this->last_modified_by = $last_modified_by;

        return $this;
    }

    public function getMinRead(): ?int
    {
        return $this->min_read;
    }

    public function setMinRead(int $min_read): self
    {
        $this->min_read = $min_read;

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

    /**
     * @return Collection|Comment[]
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comment->contains($comment)) {
            $this->comment[] = $comment;
            $comment->setBlog($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBlog() === $this) {
                $comment->setBlog(null);
            }
        }

        return $this;
    }

    public function getNotificationEmail(): ?int
    {
        return $this->notification_email;
    }

    public function setNotificationEmail(int $notification_email): self
    {
        $this->notification_email = $notification_email;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPlainContent(): ?string
    {
        return $this->plain_content;
    }

    public function setPlainContent(string $plain_content): self
    {
        $this->plain_content = $plain_content;

        return $this;
    }

    public function getTotalViews(): ?int
    {
        return $this->total_views;
    }

    public function setTotalViews(int $total_views): self
    {
        $this->total_views = $total_views;

        return $this;
    }

    public function getTodayViews(): ?int
    {
        return $this->today_views;
    }

    public function setTodayViews(int $today_views): self
    {
        $this->today_views = $today_views;

        return $this;
    }
}

<?php

namespace App\Entity\Activity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Group\Group;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionRepository")
 * 
 */
class PriceOption
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, name="title")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity\Activity", inversedBy="options")
     * @ORM\JoinColumn(name="activity", referencedColumnName="id")
     */
    private $activity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group\Group")
     * @ORM\JoinColumn(name="target", referencedColumnName="id", nullable=true)
     */
    private $target;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="json")
     */
    private $details;

    /**
     * @ORM\Column(type="string")
     */
    private $confirmationMsg;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Activity\Registration", mappedBy="option")
     */
    private $registrations;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param string $id
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get target.
     *
     * @return Group
     */
    public function getTarget(): ?Group
    {
        return $this->target;
    }

    /**
     * Set target.
     *
     * @param Group $target
     */
    public function setTarget(?Group $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function setDetails(array $details): self
    {
        $this->details = $details;

        return $this;
    }

    
    public function getConfirmationMsg(): ?string
    {
        return $this->confirmationMsg;
    }

    public function setConfirmationMsg(string $confirmationMsg): self
    {
        $this->confirmationMsg = $confirmationMsg;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function __toString()
    {
        return $this->name.' €'.number_format($this->price / 100, 2, '.', '');
    }

    /**
     * @return Collection|Registration[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setOption($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // set the owning side to null (unless already changed)
            if ($registration->getOption() === $this) {
                $registration->setOption(null);
            }
        }

        return $this;
    }
}

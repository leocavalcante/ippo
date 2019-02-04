<?php declare(strict_types=1);

namespace Acme;

class User implements \JsonSerializable
{
    private $id;
    private $name;
    private $isAdmin;
    private $birthDate;

    public function __construct(
        int $id,
        string $name,
        bool $isAdmin = false,
        ?\DateTime $birthDate = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->isAdmin = $isAdmin;
        $this->birthDate = $birthDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function withId(int $id): User
    {
        return new User(
            $id,
            $this->name,
            $this->isAdmin,
            $this->birthDate
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function withName(string $name): User
    {
        return new User(
            $this->id,
            $name,
            $this->isAdmin,
            $this->birthDate
        );
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function withIsAdmin(bool $isAdmin): User
    {
        return new User(
            $this->id,
            $this->name,
            $isAdmin,
            $this->birthDate
        );
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function withBirthDate(?\DateTime $birthDate): User
    {
        return new User(
            $this->id,
            $this->name,
            $this->isAdmin,
            $birthDate
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_admin' => $this->isAdmin,
            'birth_date' => $this->birthDate,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __clone()
    {
        return new User(
            $this->id,
            $this->name,
            $this->isAdmin,
            $this->birthDate
        );
    }

    public function __toString()
    {
        $id = json_encode($this->id);
        $name = json_encode($this->name);
        $isAdmin = json_encode($this->isAdmin);
        $birthDate = json_encode($this->birthDate);

        return "User(\n\tid => {$id};\n\tname => {$name};\n\tisAdmin => {$isAdmin};\n\tbirthDate => {$birthDate};\n)";
    }
}

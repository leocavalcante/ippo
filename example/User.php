<?php declare(strict_types=1);

namespace App;

class User implements \JsonSerializable
{
    private $id;
    private $name;
    private $email;
    private $isAdmin;
    private $birthDate;

    public function __construct(
        int $id,
        string $name,
        string $email,
        bool $isAdmin = false,
        ?\DateTime $birthDate = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
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
            $this->email,
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
            $this->email,
            $this->isAdmin,
            $this->birthDate
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function withEmail(string $email): User
    {
        return new User(
            $this->id,
            $this->name,
            $email,
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
            $this->email,
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
            $this->email,
            $this->isAdmin,
            $birthDate
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
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
            $this->email,
            $this->isAdmin,
            $this->birthDate
        );
    }

    public function __toString()
    {
        $id = json_encode($this->id);
        $name = json_encode($this->name);
        $email = json_encode($this->email);
        $isAdmin = json_encode($this->isAdmin);
        $birthDate = json_encode($this->birthDate);

        return "User(\n\tid => {$id};\n\tname => {$name};\n\temail => {$email};\n\tisAdmin => {$isAdmin};\n\tbirthDate => {$birthDate};\n)";
    }
}

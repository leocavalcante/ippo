# I.P.P.O [![CircleCI](https://circleci.com/gh/leocavalcante/ippo.svg?style=svg)](https://circleci.com/gh/leocavalcante/ippo)

- Immutable - *Uses `with`s instead of setters*
- Statically-typed - *Your tooling loves it*
- Cloneable - *No reference sharing*
- Serializable - *To JSON, to Array and to String*

― Auto-generated Plain-old PHP Objects.

## Usage

A definition file looks like:
```yaml
namespace: App

definitions:
  - User:
    id: int
    name: string
    email: string
    isAdmin: [bool, 'false']
    birthDate: [?\DateTime, 'null']
```
It's self explanatory, it declares an `User` class on the `App` namespace with `member: type` or `member: [type, default]`.
Simple like that.

You're encoraged to install it as a development dependency in your project:
```bash
$ composer install --dev leocavalcante/ippo dev-master
```

It will place a binary file at `vendor/bin` that requires only two arguments: the definition file and the output directory.
```bash
$ vendor/bin/ippo definitions.yml src/generated/
```

## Output examples
From the definitions above, you get an `User` class:
```php
class User implements \JsonSerializable
```

With a construtor like:
```php
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
```

Getters and withs for each declared attribute, like:
```php
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
```

And serialization methods like `toArray`:
```php
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
```

Or `toString()`:
```php
public function toString()
{
    $id = json_encode($this->id);
    $name = json_encode($this->name);
    $email = json_encode($this->email);
    $isAdmin = json_encode($this->isAdmin);
    $birthDate = json_encode($this->birthDate);

    return "User(\n\tid => {$id};\n\tname => {$name};\n\temail => {$email};\n\tisAdmin => {$isAdmin};\n\tbirthDate => {$birthDate};\n)";
}
```

Con·ven·ient factory methods like `fromArray` and `fromJson`:
```php
static public function fromArray(array $source): User
{
    return new User(
        $source['id'] ?? null,
        $source['name'] ?? null,
        $source['email'] ?? null,
        $source['is_admin'] ?? false,
        $source['birth_date'] ?? null,
    );
}

static public function fromJson(string $json): User
{
    $source = json_decode($json, true);

    if (false === $source) {
        throw new \InvalidArgumentException('JSON decode error: '.json_last_error_msg());
    }

    if (!is_array($source)) {
        throw new \InvalidArgumentException('Your JSON didnt decoded to an array');
    }

    return User::fromArray($source);
}
```

#### [Check out the complete output here](https://github.com/leocavalcante/ippo/blob/master/example/User.php)

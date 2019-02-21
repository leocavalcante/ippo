<?php declare(strict_types=1);

namespace LeoCavalcante\Test\Ippo;

use PHPUnit\Framework\TestCase;
use LeoCavalcante\Ippo\Ippo;

class IppoTest extends TestCase
{
    public function testGenerate()
    {
        $ippo = Ippo::fromYaml(__DIR__.'/fixture.yml');
        $definitions = $ippo->generate();

        if (file_exists(__DIR__.'/generated/TestUser.php')) {
            unlink(__DIR__.'/generated/TestUser.php');
        }

        $this->assertFalse(file_exists(__DIR__.'/generated/TestUser.php'));

        foreach ($definitions as $definition) {
            file_put_contents(__DIR__."/generated/{$definition[0]}.php", $definition[1]);
        }

        $this->assertFileExists(__DIR__.'/generated/TestUser.php');
    }

    public function testTestUser()
    {
        require_once __DIR__.'/generated/TestUser.php';

        $this->assertTrue(class_exists('\Acme\TestUser'));

        $dt = new \DateTime();
        $user = new \Acme\TestUser(1, 'username', false, $dt);

        $this->assertTrue(method_exists($user, 'getId'));
        $this->assertTrue(method_exists($user, 'withId'));
        $this->assertTrue(method_exists($user, 'getName'));
        $this->assertTrue(method_exists($user, 'withName'));
        $this->assertTrue(method_exists($user, 'getIsAdmin'));
        $this->assertTrue(method_exists($user, 'withIsAdmin'));
        $this->assertTrue(method_exists($user, 'getBirthDate'));
        $this->assertTrue(method_exists($user, 'withBirthDate'));

        $this->assertSame(1, $user->getId());
        $this->assertSame('username', $user->getName());
        $this->assertSame(false, $user->getIsAdmin());
        $this->assertSame($dt, $user->getBirthDate());

        $user2 = clone $user;

        $this->assertNotSame($user2, $user);
        $this->assertEquals($user2, $user);
        $this->assertSame(1, $user2->getId());
        $this->assertSame('username', $user2->getName());
        $this->assertSame(false, $user2->getIsAdmin());
        $this->assertSame($dt, $user2->getBirthDate());

        $this->assertStringStartsWith("TestUser(\n\tid => 1;\n\tname => \"username\"", $user->toString());

        $userArr = $user->toArray();

        $this->assertTrue(is_array($userArr));
        $this->assertSame([
            'id' => 1,
            'name' => 'username',
            'is_admin' => false,
            'birth_date' => $dt,
        ], $userArr);

        $userJson = json_encode($user);
        $this->assertStringStartsWith('{"id":1,"name":"username","is_admin":false,"birth_date":{"date":"', $userJson);
    }

    public function testFromArray()
    {
        $user = \Acme\TestUser::fromArray([
            'name' => 'from_array',
            'id' => 1,
            'foo' => 'bar',
        ]);

        $this->assertSame(1, $user->getId());
        $this->assertSame('from_array', $user->getName());
        $this->assertFalse($user->getIsAdmin());
        $this->assertNull($user->getBirthDate());
    }

    public function testFromJson()
    {
        $user = \Acme\TestUser::fromJson('{"id": 1, "name": "from_json", "foo": "bar"}');

        $this->assertSame(1, $user->getId());
        $this->assertSame('from_json', $user->getName());
        $this->assertFalse($user->getIsAdmin());
        $this->assertNull($user->getBirthDate());
    }

    public function testFromJsonInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        \Acme\TestUser::fromJson('not a json');
    }

    public function testFromJsonNotArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        \Acme\TestUser::fromJson('"valid json, but not an array"');
    }
}

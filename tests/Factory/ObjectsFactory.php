<?php

namespace App\Tests\Factory;

use App\Entity\Objects;
use App\Repository\ObjectsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Objects>
 *
 * @method        Objects|Proxy                     create(array|callable $attributes = [])
 * @method static Objects|Proxy                     createOne(array $attributes = [])
 * @method static Objects|Proxy                     find(object|array|mixed $criteria)
 * @method static Objects|Proxy                     findOrCreate(array $attributes)
 * @method static Objects|Proxy                     first(string $sortedField = 'id')
 * @method static Objects|Proxy                     last(string $sortedField = 'id')
 * @method static Objects|Proxy                     random(array $attributes = [])
 * @method static Objects|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ObjectsRepository|RepositoryProxy repository()
 * @method static Objects[]|Proxy[]                 all()
 * @method static Objects[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Objects[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Objects[]|Proxy[]                 findBy(array $attributes)
 * @method static Objects[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Objects[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ObjectsFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $name = self::faker()->text(10);
        return [
            'name' => $name,
            'quantity' => self::faker()->randomNumber(),
            'quantityTrigger' => self::faker()->randomNumber(),
            'slug'=> strtolower(str_replace(" ","-",$name)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Objects $objects): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Objects::class;
    }

    public static function createArray(): array
    {
        $name = self::faker()->text(10);
        return [
            'name' => $name,
            'quantity' => self::faker()->randomNumber(),
            'quantityTrigger' => self::faker()->randomNumber(),
            'slug'=> strtolower(str_replace(" ","-",$name)),
        ];
    }

}

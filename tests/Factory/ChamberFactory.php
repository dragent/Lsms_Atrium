<?php

namespace App\Tests\Factory;

use App\Entity\Chamber;
use App\Repository\ChamberRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Chamber>
 *
 * @method        Chamber|Proxy                     create(array|callable $attributes = [])
 * @method static Chamber|Proxy                     createOne(array $attributes = [])
 * @method static Chamber|Proxy                     find(object|array|mixed $criteria)
 * @method static Chamber|Proxy                     findOrCreate(array $attributes)
 * @method static Chamber|Proxy                     first(string $sortedField = 'id')
 * @method static Chamber|Proxy                     last(string $sortedField = 'id')
 * @method static Chamber|Proxy                     random(array $attributes = [])
 * @method static Chamber|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ChamberRepository|RepositoryProxy repository()
 * @method static Chamber[]|Proxy[]                 all()
 * @method static Chamber[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Chamber[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Chamber[]|Proxy[]                 findBy(array $attributes)
 * @method static Chamber[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Chamber[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ChamberFactory extends ModelFactory
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
        $name = 654;
        return [
            'name' => $name,
            'price' => self::faker()->randomNumber(),
            'slug' => $name,
            'type' => self::faker()->numberBetween(0,2),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Chamber $chamber): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Chamber::class;
    }

    public static function createArray(): array
    {
        $name = 654;
        return [
            'name' => $name,
            'price' => self::faker()->randomNumber(),
            'type' => self::faker()->numberBetween(0,2),
            'slug'=> $name,
        ];
    }
}

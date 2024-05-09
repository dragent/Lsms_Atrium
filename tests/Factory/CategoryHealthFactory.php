<?php

namespace App\Tests\Factory;

use App\Entity\CategoryHealth;
use App\Repository\CategoryHealthRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<CategoryHealth>
 *
 * @method        CategoryHealth|Proxy                     create(array|callable $attributes = [])
 * @method static CategoryHealth|Proxy                     createOne(array $attributes = [])
 * @method static CategoryHealth|Proxy                     find(object|array|mixed $criteria)
 * @method static CategoryHealth|Proxy                     findOrCreate(array $attributes)
 * @method static CategoryHealth|Proxy                     first(string $sortedField = 'id')
 * @method static CategoryHealth|Proxy                     last(string $sortedField = 'id')
 * @method static CategoryHealth|Proxy                     random(array $attributes = [])
 * @method static CategoryHealth|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CategoryHealthRepository|RepositoryProxy repository()
 * @method static CategoryHealth[]|Proxy[]                 all()
 * @method static CategoryHealth[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static CategoryHealth[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static CategoryHealth[]|Proxy[]                 findBy(array $attributes)
 * @method static CategoryHealth[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static CategoryHealth[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CategoryHealthFactory extends ModelFactory
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
        $name = self::faker()->name();
        return [
            'name' => self::faker()->text(100),
            'position' => self::faker()->randomNumber(),
            'slug'=> strtolower(str_replace(" ","-",$name)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(CategoryHealth $categoryHealth): void {})
        ;
    }

    protected static function getClass(): string
    {
        return CategoryHealth::class;
    }

    public static function createArray(): array
    {
        $name = self::faker()->name();
        return [
            'name' => $name,
            'position' => self::faker()->numberBetween(0,10),
            'slug'=> strtolower(str_replace(" ","-",$name)),
        ];
    }
}

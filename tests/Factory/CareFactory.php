<?php

namespace App\Tests\Factory;

use App\Entity\Care;
use App\Repository\CareRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Care>
 *
 * @method        Care|Proxy                     create(array|callable $attributes = [])
 * @method static Care|Proxy                     createOne(array $attributes = [])
 * @method static Care|Proxy                     find(object|array|mixed $criteria)
 * @method static Care|Proxy                     findOrCreate(array $attributes)
 * @method static Care|Proxy                     first(string $sortedField = 'id')
 * @method static Care|Proxy                     last(string $sortedField = 'id')
 * @method static Care|Proxy                     random(array $attributes = [])
 * @method static Care|Proxy                     randomOrCreate(array $attributes = [])
 * @method static CareRepository|RepositoryProxy repository()
 * @method static Care[]|Proxy[]                 all()
 * @method static Care[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Care[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Care[]|Proxy[]                 findBy(array $attributes)
 * @method static Care[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Care[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class CareFactory extends ModelFactory
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
            'name' => $name,
            'price' => self::faker()->randomNumber(),
            'slug'=> strtolower(str_replace(" ","-",$name)),
            'category'=> CategoryHealthFactory::createOne(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Care $care): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Care::class;
    }

    public static function createArray(): array
    {
        $name = self::faker()->name();
        return [
            'name' => $name,
            'price' => self::faker()->randomNumber(),
            'slug'=> strtolower(str_replace(" ","-",$name)),
            'category'=> CategoryHealthFactory::createOne(),
        ];
    }
}

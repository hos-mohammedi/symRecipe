<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    private $faker;
    private $counter = 0;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        //ingredients
        for ($i = 0; $i <= 50; $i++) {
            $ingredient = (new Ingredient())
                ->setName($this->faker->word())
                ->setPrice(rand(1, 200));
            $manager->persist($ingredient);
            $this->addReference('ingredient-'. $this->counter, $ingredient);
            $this->counter++;
        }

        // recettes
        for($j = 0; $j <= 25; $j++) {
            $recette = (new Recette())
                ->setName($this->faker->word())
                ->setTime(rand(1, 24))
                ->setNbrPersonnes(mt_rand(1, 50))
                ->setDifficulty(mt_rand(1, 5))
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
                ->addIngredient($this->getReference('ingredient-' . rand(1, 50)))
            ;
            $manager->persist($recette);
        }
        $manager->flush();
    }
}

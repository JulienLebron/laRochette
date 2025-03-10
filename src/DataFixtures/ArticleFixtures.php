<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Créer 3 fausses Catégories
        for($i = 1; $i <= 3; $i++) {
            $category = new Category;
            $category->setTitle($faker->sentence())
                    ->setDescription("Nouvelle Catégories");

            $manager->persist($category);

            // Créer entre 4 et 6 articles
            for($j = 1; $j <= mt_rand(4,6); $j++) {
                $article = new Article;
                $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';
                $generatedId = mt_rand(1, 200);

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage("https://picsum.photos/id/$generatedId/200/150")
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
                $manager->persist($article);

                for($k = 1; $k <= mt_rand(4,10); $k++) {
                    $comment = new Comment;
                    $content = '<p>' . join('</p><p>', $faker->paragraphs(2)) . '</p>';

                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $minimum = '-' . $days . ' days';

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }
        }
       $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for($i = 1; $i <= 10; $i++) {
            // On instancie la class Article() qui se trouve dans le dossier App\Entity
            $article = new Article();

            // Nous pouvons maintenant faire appel au setter pour créer des articles
            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium magnam ipsam asperiores accusamus obcaecati, suscipit totam saepe. Vero quisquam reiciendis sunt reprehenderit repudiandae porro quibusdam, similique, architecto, ea commodi delectus.</p>")
                    ->setImage("https://picsum.photos/250/150")
                    ->setCreatedAt( new \DateTime()); // On instancie la class DateTime() pour formater la date
            $manager->persist($article); // permet de faire persister l'article dans le temps
        }

        // $product = new Product();
        // $manager->persist($product);

        // La méthode flush() lance la requête SQL qui va enregistrer les articles en BDD
        $manager->flush();
    }
}

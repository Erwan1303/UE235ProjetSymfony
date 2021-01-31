<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use ProxyManager\ProxyGenerator\ValueHolder\MethodGenerator\Constructor;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AppFixtures extends Fixture
{
    // mise en place de l'encodeur pour hasher les mots de passe
    protected $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $admin = new User;

        //encodege du mot de passe de l'admin
        $hash = $this->encoder->encodePassword($admin, "password");

        $admin->setEmail("admin@gmail.com")
            ->setPassword("$hash")
            ->setFullname("Admin")
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);


        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            // encodage du mote de passe des users
            $hash = $this->encoder->encodePassword($user, "password");

            $user->setEmail("user$u@gmail.com")
                ->setFullName("Personna$u")
                ->setPassword("$hash");
            $manager->persist($user);
        }

        $category = new Category();
        $category->setName("Catégorie 1");
        $manager->persist($category);

        $article = new Article();
        $article->setTitle('Article 1');
        $article->setContent('Proinde die funestis interrogationibus praestituto imaginarius iudex equitum resedit magister adhibitis aliis iam quae essent agenda praedoctis, et adsistebant hinc inde notarii, quid quaesitum esset, quidve responsum, cursim ad Caesarem perferentes, cuius imperio truci, stimulis reginae exsertantis aurem subinde per aulaeum, nec diluere obiecta permissi nec defensi periere conplures.');
        $article->setCategory($category);
        $manager->persist($article);

        $article = new Article();
        $article->setTitle('Article 2');
        $article->setContent('Proinde die funestis interrogationibus praestituto imaginarius iudex equitum resedit magister adhibitis aliis iam quae essent agenda praedoctis, et adsistebant hinc inde notarii, quid quaesitum esset, quidve responsum, cursim ad Caesarem perferentes, cuius imperio truci, stimulis reginae exsertantis aurem subinde per aulaeum, nec diluere obiecta permissi nec defensi periere conplures.');
        $article->setCategory($category);
        $manager->persist($article);

        $manager->flush();
    }
}

# sym64prepG2

## Installation Symfony 6.4

    symfony new sym64prepG2 --version=lts --webapp

## Base du projet

On redémarre le projet en récupérant les fichiers suivants du dossier (vous ne devrez pas le faire dans le TI) :

https://github.com/WebDevCF2m2023/exeSymG2

- src/Controller/SecurityController.php
- src/Entity/Comment.php
- src/Entity/Post.php
- src/Entity/Section.php
- src/Entity/Tag.php
- src/Entity/User.php
- src/Repository/CommentRepository.php
- src/Repository/PostRepository.php
- src/Repository/SectionRepository.php
- src/Repository/TagRepository.php
- src/Repository/UserRepository.php
- templates/security/

### Modification du `.env`

```env
# .env
# ...
# Variables pour Docker A METTRE DANS le .env.local !!!
DB_TYPE="mysql"
DB_NAME="sym64prepg2"
DB_HOST="localhost"
DB_PORT=3306
DB_USER="root"
DB_PWD=""
DB_CHARSET="utf8mb4"

DATABASE_URL="${DB_TYPE}://${DB_USER}:${DB_PWD}@${DB_HOST}:${DB_PORT}/${DB_NAME}?charset=${DB_CHARSET}"
# ...
```

### Création d'un contrôleur pour les principales vues publiques

    php bin/console make:controller MainController

Dans `src/Controller/MainController.php` on modifie le nom et la route :

```php
class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
```

### Création de la database

Ouvrez Wamp si non `dockerisé`

    php bin/console d:d:c

Puis création d'une première migration :

    php bin/console ma:mi

Exécution de la migration :

    php bin/console d:m:m

### Modification des entités

#### User

On veut ajouter des champs :

- userEmail string 160 NOT NULL
- userActive boolean default: false NOT NULL
- userUniqueKey string 255 NOT NULL
- userFullName string 200 NULL


    php bin/console make:entity User

On va modifier notre fichier :

```php
// src/Entity/User.php
# ....
   // #[ORM\Column(length: 160)]
   // private ?string $userEmail = null;
   // en
   #[ORM\Column(
        length: 160,
        unique: true)]
    private ?string $userEmail = null;
    
   // #[ORM\Column]
   // private ?bool $userActive = null;
   // en
   #[ORM\Column(
        type: 'boolean',
        options: ['default' => false]
    )]
    private ?bool $userActive = null;

    #[ORM\Column(length: 255)]
    private ?string $userUniqueKey = null;
    
    #[ORM\Column(length: 200, nullable: true)]
    private ?string $userFullName = null;
    
    
# ....
```

#### Section

On veut ajouter le champ :

- sectionSlug string 162 NOT NULL UNIQUE

        php bin/console make:entity Section

On va modifier notre fichier :

```php
// src/Entity/Section.php
# ...
   // #[ORM\Column(length: 162)]
   // private ?string $sectionSlug = null;
   // en 
   #[ORM\Column(
        length: 162,
        unique: true,
    )]
    private ?string $sectionSlug = null;
# ...
```

#### Post

On veut ajouter le champ :

- postSlug string 162 NOT NULL UNIQUE

      php bin/console make:entity Post

```php
// src/Entity/Post.php
# ....
  // #[ORM\Column(length: 162)]
  //   private ?string $postSlug = null;
  // en
  #[ORM\Column(
        length: 162,
        unique: true,
    )]
    private ?string $postSlug = null;
```

On va mettre à jour notre DB :

Puis création d'une migration :

    php bin/console ma:mi

Exécution de la migration :

    php bin/console d:m:m

### Créez les `Fixtures`

    composer require --dev orm-fixtures

    # php bin/console make:fixtures

#### Puis `Faker` :

    composer require fakerphp/faker

https://fakerphp.org/

#### Puis `Slugify` :


    composer require cocur/slugify

https://packagist.org/packages/cocur/slugify

### AppFixtures.php

Ouvrez le fichier `src/DataFixtures/AppFixtures.php`

```php
<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
# Entité User
use App\Entity\User;
# Entité Post
use App\Entity\Post;
# Entité Section
use App\Entity\Section;
# Entité Comment
use App\Entity\Comment;
# Entité Tag
use App\Entity\Tag;

# chargement du hacher de mots de passe
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

# chargement de Faker et Alias de nom
# pour utiliser Faker plutôt que Factory
# comme nom de classe
use Faker\Factory AS Faker;

# chargement de slugify
use Cocur\Slugify\Slugify;

class AppFixtures extends Fixture
{
    // Attribut privé contenant le hacheur de mot de passe
    private UserPasswordHasherInterface $hasher;

    // création d'un constructeur pour récupérer le hacher
    // de mots de passe
    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->hasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $slugify = new Slugify();

        ###
        #
        # INSERTION de l'admin avec mot de passe admin
        #
        ###
        // création d'une instance de User
        $user = new User();

        // création de l'administrateur via les setters
        $user->setUsername('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setUserEmail('michael.pitz@cf2m.be');
        $user->setUserFullName("Pitz Michaël");
        $user->setUserActive(true);
        $user->setUserUniqueKey(uniqid('user_', true));
        // on va hacher le mot de passe
        $pwdHash = $this->hasher->hashPassword($user, 'admin');
        // passage du mot de passe crypté
        $user->setPassword($pwdHash);

        // on va mettre dans une variable de type tableau
        // tous nos utilisateurs pour pouvoir leurs attribués
        // des Post ou des Comment
        $users[] = $user;

        // on prépare notre requête pour la transaction
        $manager->persist($user);

        ###
        #
        # INSERTION de 10 utilisateurs en ROLE_USER
        # avec nom et mots de passe "re-tenables"
        #
        ###
        for($i=1;$i<=10;$i++){
            $user = new User();
            // username de : user0 à user10
            $user->setUsername('user'.$i);
            $user->setRoles(['ROLE_USER']);
            $user->setUserEmail($faker->email());
            $user->setUserFullName($faker->name());
            $user->setUserActive(true);
            $user->setUserUniqueKey(uniqid('user_', true));
            // hashage du mot de passe de : user0 à user10
            $pwdHash = $this->hasher->hashPassword($user, 'user'.$i);
            $user->setPassword($pwdHash);
            // on récupère les utilisateurs pour
            // les post et les comments
            $users[]=$user;
            $manager->persist($user);
        }


        ###
        #   POST
        # INSERTION de Post avec leurs users
        #
        ###

        for($i=1;$i<=100;$i++){
            $post = new Post();
            // on prend une clef d'un User
            // créé au-dessus
            $keyUser = array_rand($users);
            // on ajoute l'utilisateur
            // à ce post
            $post->setUser($users[$keyUser]);
            // date de création (il y a 30 jours)
            $post->setPostDateCreated(new \dateTime('now - 30 days'));
            // Au hasard, on choisit s'il est publié ou non (+-3 sur 4)
            $publish = mt_rand(0,3) <3;
            $post->setPostPublished($publish);
            if($publish) {
                $day = mt_rand(3, 25);
                $post->setPostDatePublished(new \dateTime('now - ' . $day . ' days'));
            }
            // création d'un titre entre 2 et 5 mots
            $title = $faker->words(mt_rand(2,5),true);
            // utilisation du titre avec le premier mot en majuscule
            $post->setPostTitle(ucfirst($title));
            // on va slugifier le title
            $post->setPostSlug($slugify->slugify($title));

            // création d'un texte entre 3 et 6 paragraphes
            $texte = $faker->paragraphs(mt_rand(3,6), true);
            $post->setPostDescription($texte);

            // on va garder les posts
            // pour les Comment, Section et Tag
            $posts[]=$post;

            $manager->persist($post);

        }

        ###
        #   SECTION
        # INSERTION de Section en les liants
        # avec des postes au hasard
        #
        ###

        for($i=1;$i<=6;$i++){
            $section = new Section();
            // création d'un titre entre 2 et 5 mots
            $title = $faker->words(mt_rand(2,5),true);
            // titre
            $section->setSectionTitle(ucfirst($title));
            // on slugifie le titre
            $section->setSectionSlug($slugify->slugify($title));
            // création d'une description de maximum 500 caractères
            // en pseudo français du fr_FR
            $description = $faker->realText(mt_rand(150,500));
            $section->setSectionDescription($description);

            // On va mettre dans une variable le nombre total d'articles
            $nbArticles = count($posts);
            // on récupère un tableau d'id au hasard
            $articleID = array_rand($posts, mt_rand(1,$nbArticles));

            // Attribution des articles
            // à la section en cours
            foreach($articleID as $id){
                // entre 1 et 100 articles
                $section->addPost($posts[$id]);
            }

            $manager->persist($section);
        }

        ###
        #   COMMENT
        # INSERTION de Comment en les liants
        # avec des Post au hasard et des User
        #
        ###
        // on choisit le nombre de commentaires entre 250 et 350
        $commentNB = mt_rand(250,350);
        for($i=1;$i<=$commentNB;$i++){

            $comment = new Comment();
            // on prend une clef d'un User
            // créé au-dessus au hasard
            $keyUser = array_rand($users);
            // on ajoute l'utilisateur
            // à ce commentaire
            $comment->setUser($users[$keyUser]);
            // on prend une clef d'un Post
            // créé au-dessus au hasard
            $keyPost = array_rand($posts);
            // on ajoute l'article
            // de ce commentaire
            $comment->setPost($posts[$keyPost]);
            // écrit entre 1 et 48 heures
            $hours = mt_rand(1,48);
            $comment->setCommentDateCreated(new \dateTime('now - ' . $hours . ' hours'));
            // entre 150 et 1000 caractères
            $comment->setCommentMessage($faker->realText(mt_rand(150,1000)));
            // Au hasard, on choisit s'il est publié ou non (+-3 sur 4)
            $publish = mt_rand(0,3) <3;
            $comment->setCommentPublished($publish);

            $manager->persist($comment);
        }

        // validation de la transaction
        $manager->flush();
    }
}
```




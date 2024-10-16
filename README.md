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

Ce qui va modifier notre fichier :

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
    




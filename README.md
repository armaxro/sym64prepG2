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

### Modification des entités


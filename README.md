# Site web - R. Oncedor

Le site est destiné à l'autrice R. Oncedor afin de lui permettre de disposer de son propre espace web pour se présenter, présenter ses ouvrages et vendre certains d'entre-eux avec un système de précommandes.

## Environnement de développement

* PHP 7.4 (min)
* Symfony CLI
* MySQL
* NPM

*Commande symfony pour checker les pré-requis :*
```
symfony check:requirements
```

### Installation du projet

* Pour installer les dépendances php du projet :
```
composer install
```
* Pour installer les dépendances js du projet :
```
npm install
```
* Configurer les variables locales dans le .env
    * mailer dsn
    * database
* Effectuer les migrations vers la base de données :
```
symfony console make:migration
symfony console doctrine:migrations:migrate
```

### Lancement de l'environnement de dev

* Pour lancer le serveur interne de symfony :
```
symfony serve
```
* Pour build les fichiers scss pour le dev :
```
npm run build --dev
```
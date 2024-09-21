# ToDo & Co - Améliorez une application existante de ToDo & Co

Dans le cadre du projet n°8 de la formation Développeur d'application - PHP/Symfony de OpenClassrooms,
ce site web permet aux utilisateurs de créer et gérer une todo list.


## PRÉREQUIS

- PHP version 8.2 ou supérieur
- Symfony 6.4.11
- Apache server version 2.4 ou supérieur
- Composer


## INSTALLATION

- Cloner le projet sur GitHub [Lien vers le projet GitHub](https://github.com/Saydrick/ToDo_And_Co) et l’ajouter dans le dossier des projets de votre environnement de serveur apache local avec la commande :
```
git clone https://github.com/Saydrick/ToDo_And_Co.git
```
- Créer une base de données en local nommée "todo_and_co" et importer le fichier "todo_and_co.sql" qui se trouve à la racine du projet.
- Mettre à jour le fichier `.env` avec les identifiants de connexion à votre base de données.
- Exécuter `composer install` à la racine du projet pour installer les bibliothèques du projet.

## UTILISATION

### Connexion
Se connecter sur le site avec les identifiants de connexion suivants :

Utilisateur :
- Nom d'utilisateur : user
- mot de passe : password

Admin :
- Nom d'utilisateur : admin
- mot de passe : password


### Fonctionnalités
Les principales fonctionnalités du projet accessibles suivant le type d'utilisateur connecté sont :

Tous types d'utilisateurs (connectés ou non) :
- Inscription / connexion / déconnexion du site.

Utilisateur connecté :
- Consulter la liste des tâches à faire.
- Consulter la liste des tâches complétées.
- Ajout d'une nouvelle tâche.
- Modification d'une tâche existante.
- Suppression d'une tâche existante.
- Validation / invalidation d'une tâche.

Administrateur connecté :
- Ajout d'utilisateur.
- Modification d'utilisateur existant.
- Gestion des tâches "anonyme".

## BIBLIOTHÈQUES UTILISÉES

Twig

## LANCEMENT DES TESTS

Les tests unitaires et fonctionnels sont gérés avec PHPUnit, ils génèrent un détail des tests au format HTML dans le dossier "public/test-coverage".

Pour lancer les tests sur une classe précise, utiliser la commande (en modifiant le nom du controller) :
```
vendor/bin/phpunit --filter NomControllerTest
```
Pour générer le détail au format HTML, utiliser la commande :
```
vendor/bin/phpunit --coverage-html public/test-coverage
```

## BADGE SYMFONY INSIGHT
[![SymfonyInsight](https://insight.symfony.com/projects/2539b01e-130b-4353-9444-8caeee9f717d/big.svg)](https://insight.symfony.com/projects/2539b01e-130b-4353-9444-8caeee9f717d)

## AUTEUR

BOUZANQUET Cédric

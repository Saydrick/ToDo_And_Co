# Guide de Contribution

Merci de contribuer à ce projet ! Ce guide détaille les étapes à suivre pour apporter des modifications tout en garantissant la qualité du code et le respect des normes du projet.

## 1. Comment proposer une modification

1. **Fork du projet** : 
   - Forkez le projet sur GitHub et clonez-le sur votre machine locale :
     ```bash
     git clone https://github.com/Saydrick/ToDo_And_Co.git
     cd ToDo_And_Co
     ```
2. **Création d’une branche** :
   - Utilisez des noms de branches explicites basés sur la tâche ou l’issue. Par exemple :
     - `feature/ajout-fonctionnalite` pour une nouvelle fonctionnalité
     - `fix/issue-123` pour corriger un bug identifié.
   - Pour créer une branche :
     ```bash
     git checkout -b feature/ajout-fonctionnalite
     ```

3. **Modifications et respect des normes** :
   - Effectuez les changements dans le code, en respectant les normes **PSR-12** (conventions de codage PHP).
   - Pensez à documenter le code, notamment les parties complexes.

4. **Tests** :
   - Vérifiez que vos modifications n’introduisent pas de régressions en ajoutant des **tests unitaires** et **fonctionnels** avec **PHPUnit**.
   - Pour exécuter les tests localement :
     ```bash
     vendor/bin/phpunit
     ```

5. **Soumettre une Pull Request (PR)** :
   - Une fois vos modifications prêtes, poussez-les sur votre fork GitHub et soumettez une **Pull Request** vers la branche de développement (`develop` ou `main`).
   - Assurez-vous d’inclure une description claire et de mentionner l’issue que vous réglez (par exemple : `Fixes #123`).

## 2. Bonnes pratiques de codage

- **Normes de codage** : 
  - Toutes les contributions doivent respecter la norme **PSR-12**. Cela inclut l’indentation, les espaces, les noms de variables et de fonctions, ainsi que les commentaires dans le code.
- **Messages de commit** :
  - Utilisez des messages clairs et concis. Exemple : 
    - `Ajouter fonctionnalité de connexion`
    - `Corriger affichage des erreurs de connexion`

## 3. Structure des branches

- **Branche principale** (`main`) :
  - Contient la dernière version stable du projet.
- **Branche de développement** (`develop`) :
  - Regroupe les nouvelles fonctionnalités qui seront fusionnées après validation.
- **Branches de fonctionnalités et corrections** :
  - Pour chaque nouvelle fonctionnalité, créez une branche avec le préfixe `feature/`.
  - Pour les corrections de bugs, utilisez `fix/`.

## 4. Processus de revue et fusion des Pull Requests

1. **Revue de code** :
   - Toute **Pull Request** doit être revue par un autre développeur avant d’être fusionnée. Si des modifications sont demandées, effectuez-les sur la même branche.
   
2. **Tests obligatoires** :
   - Toutes les PR doivent passer les tests **unitaires** et **fonctionnels** avant d'être examinées. Assurez-vous que la couverture de test est correcte.

3. **Gestion des conflits** :
   - Si des conflits de merge surviennent, résolvez-les avant de soumettre la PR. Utilisez :
     ```bash
     git merge develop
     ```

## 5. Couverture de tests et qualité du code

- Le projet utilise **PHPUnit** pour les tests.
- Le taux de couverture des tests est suivi avec **Xdebug**. Essayez de maintenir un niveau de couverture élevé pour garantir la robustesse du code.
  
## 6. Normes de sécurité

- Si vous découvrez une vulnérabilité de sécurité, ne créez pas d’issue publique. Contactez l’équipe par email à [votre-email] pour signaler la faille.

Merci de suivre ces étapes afin de contribuer efficacement au projet !

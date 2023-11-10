# SAE Développement d'une application Web sécurisée

- [Tableau de bord](#tableauDeBord)
- [Données pour le fonctionnement de l'application](#donnees)

## Tableau de bord
### Présentation du groupe

- Luc Dechezleprêtre (Pseudos git : LucDechezlepretre & Lucoss)
- Liam Despaquis (Pseudo git : LiamDespaquis)
- Antoine Fontanez (Pseudo git : Akip2)
- Robin Slimani (Pseudo git : RobinSlimani & RobinSlimani)

### Fonctionnalités réalisées 

#### Fonctionnalités pour les utilisateurs

1. Afficher la liste des touites : Liam, Luc
2. Afficher un touite en détail : Liam
3. Paginer la liste des touites : Liam
4. Afficher les touites d’une personne donnée : Liam, Robin
5. Afficher les touites associer à un tag : Liam
6. Créer un compte : Luc
7. Se connecter : Luc

#### Les fonctionnalités pour les utilisateurs authentifiés

8. Publier un touite : Antoine
9. Évaluer un touite : Antoine, Luc, Robin
10. Évaluer un touite : Antoine, Robin
11. La gestion des tags : Antoine
12. Le mur d’un utilisateur : Robin
13. Suivre des utilisateurs : Robin
14. S’abonner à un tag : Antoine
15. L’utilisateur narcissique : Luc
16. Associer une image à un touite : Antoine
 
#### Les fonctionnalités pour les administrateurs

17. Les influenceurs : Luc
18. Les tendances : Luc
19. Le back-office : Luc

#### Les fonctionnalités supplémentaires au sujet

- CSS : Antoine, Luc
- Logo : Robin

## Données pour le fonctionnement de l'application 
### Compte utilisateur
-
-
### Compte administrateur
-

## Présentation de l'application

Notre application Touiter propose à un utilisateur non connecté à un compte de pouvoir consulter l'entièreté des touites présent sur la plateforme. Il peut aussi s'inscrire ou se connecter à un compte. Une fois connecté l'utilisateur peut publier un touite (avec une image ou non), voir son profil, suivre des tags (déjà publiés au préalables), voir tous les touites présent sur la plartforme ou se déconnecter. Un utilisateur peut cliquer sur "voir plus" pour afficher le touite dans son intégralité, de plus il peut cliquer sur le nom de l'utilisateur pour afficher les touites de l'utilisateur, suivre l'utilisateur, liker ou disliker le touite. 

## Présentation du code

### Action

#### Classe Action
La classe **Action** est une classe abstraite qui possède trois attributs protégé : http_method (qui stocke le type de requête qui appelle l'action), hostname (pour le nom de l'host) et script_name (pour le nom du script). Le constructeur de la classe initialise tous les attributs avec le tableau _$_SERVER_. La seule méthode de la classe est la méthode _execute()_ qui renvoie un string qui contient le code **html** nécessaire à l'action ou résultat de l'action.
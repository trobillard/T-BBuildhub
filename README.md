#Application de gestion de projet sous Symfony (ou todolist)

Il s'agit d'une application développée dans le cadre de mon poste de formateur en développement web. L'objectif est que les apprenants produisent une application bancaire permettant à un utilisateur de gérer ses comptes à l'aide du framework Symfony.

Au travers de ce projet, ils apprennent à :

Maquetter une application
Réaliser une interface utilisateur web statique et adaptable
Développer une interface utilisateur web dynamique
Créer une base de données
Développer les composants d’accès aux données
Développer la partie back-end d’une application web ou web mobile
Elaborer et mettre en œuvre des composants dans une application de gestion de contenu ou e-commerce
Consignes

Développeur web PHP dans une agence, on vous a confié la réalisation d’une application métier pour un client travaillant dans le bâtiment.

Jusqu’à maintenant, les conducteurs de travaux de ce client utilisent des cahiers, des tableaux blancs et des post-it pour gérer le dérouler des projets de construction et les différentes deadlines pour les étapes du chantier.

Le problème avec ce système, bien que fonctionnel, est qu’il est coûteux, peu sécurisé et demande de la place. Le client souhaite donc développer une application permettant à ses conducteurs de travaux de gérer leurs projets de manière informatisée.

Spécifications fonctionnelles :

Pour ce faire, vous devez concevoir une application qui permet à l’utilisateur de :

Se connecter avec son compte personnel ou de s’inscrire s’il n’a pas de compte
Voir tous les projets de cet utilisateur sur une page
Créer un nouveau projet via un formulaire
Voir le détail d’un projet, c’est-à-dire le projet avec ses tâches quand il clique dessus
Créer des tâches liées à un projet particulier via un formulaire
Supprimer les projets et les tâche comme il le désire
Indiquer si une tâche est terminée
Distinguer visuellement les tâches finies des tâches en cours
Voir projets et tâches classées par ordre de deadline
Utiliser l’application sur les chantiers via une tablette ou un smartphone
L’application est également visuellement enrichie afin d’offrir à l’utilisateur une expérience la plus intuitive possible. Par exemple :

Au survol d’un projet tous les autres projets sauf celui-ci se grisent
L’utilisateur peut choisir de cacher temporairement un projet ou une tâche d’un projet
Un boutton d'aide fait appraître sous forme de popup ou de layer des instructions d'utilisation
Quelques information supplémentaires :

Un utilisateur se connecte avec un email et un mot de passe
Un projet est composé à minima d’un nom, d’une description, d’une date de création, d’une deadline et d'un statut
Une tâche est composée à minima d’un nom, d’une description, d’une date de création, d’une deadline et d’un statut
En plus de votre application, on trouvera dans votre projet dans un dossier documentation :

Un schéma de type UseCase et une arborescence fonctionnelle de l’application reprenant les fonctionnalités de chaque page
Un schéma de base de données listant les tables, leur contenu, leurs relations et les cardinalités
Un diagramme de classes qui liste les entité de votre applications, leur contenu et leurs relations
Les wireframes de l’application qui spécifient à minima le template et la page principale pour les version mobiles, tablettes et PC
Spécifications techniques :

Framework Symfony 5
Sécurité de l’application gérée avec le bundle de sécurité Symfony
Idéalement vous générez les données à l’aide de fixtures
JavaScript ES6

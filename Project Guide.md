# Event Planner -- Cahier des charges du projet

## **All generated files' names must start with 'AAB_' signifying Ahmed Adam Boussarsar initials**

## 1. Présentation générale

**Event Planner** est une application web développée avec Laravel
permettant la gestion complète d'événements.

Elle inclut deux types de rôles: Administrateur et Utilisateur simple.

L'administrateur gère entièrement les événements, tandis que
l'utilisateur peut consulter les événements, s'y inscrire s'il reste des
places, et accéder à la liste de ses inscriptions.

## 2. Rôles et permissions

● **Rôle Administrateur :**

\- Créer un événement

\- Modifier un événement

\- Supprimer un événement

\- Gérer les catégories

\- Voir toutes les inscriptions des utilisateurs

● **Rôle Utilisateur simple :**

\- Consulter les événements (avec pagination ou bouton "Voir plus",
recherche et filtre)

\- S'inscrire à un événement si des places sont disponibles

\- Se désinscrire d'un événement (optionnel)

\- Voir la liste de ses inscriptions

## 3. Fonctionnalités principales

● Gestion des événements (CRUD Admin uniquement) :

\- Titre

\- Description

\- Date et heure

\- Lieu

\- Nombre de places disponibles

\- Catégorie (relation)

\- Prix (peut être gratuit)

\- Image d'illustration

\- Statut (actif / archivé)

● **Inscriptions aux événements :**

\- Un utilisateur peut s'inscrire si des places sont disponibles

\- Le nombre de places restantes est mis à jour automatiquement

\- Un même utilisateur ne peut s'inscrire qu'une seule fois par
événement

● **Page personnelle utilisateur :**

\- Liste des événements auxquels il est inscrit

● **Catégories :**

\- CRUD réservé à l'administrateur

\- Un événement appartient obligatoirement à une catégorie

## 4. Modèles et relations

**Modèle User :**

\- id, name, email, password, role

\- profile_image, phone (optionnel)

**Modèle Event :**

\- id, title, description, start_date, end_date, place, price,
category_id, capacity, image, created_by, is_free

**Modèle Category :**

\- id, name

**Modèle Registration :**

\- id, user_id, event_id, created_at

**Relations :**

\- User hasMany Event

\- Event belongsTo User

\- Event belongsTo Category

\- User belongsToMany Event (via Registration)

\- Event hasMany Registration

## 6. Pages à développer

● **Pages publiques :**

\- Liste des événements

\- Détail d'un événement (+ inscription à un évènement par un
utilisateur)

● **Espace utilisateur :**

\- Profile utilisateur **(optionnel)**

\- Liste de mes inscriptions

● **Espace administrateur :**

\- Dashboard admin avec la list de toutes les inscriptions
**(optionnel)**

\- Gestion des événements (CRUD)

\- Gestion des catégories (CRUD)

● **Authentification :**

\- Login / Register / Logout

## 7. Contraintes et règles

● Un compte administrateur initial doit être inséré à travers un seeder.

● Un utilisateur ne peut pas s'inscrire à un evenement si le quota est
atteint.

● Un utilisateur ne peut s'inscrire à un evenement que s'il est
connecté.

● La date de début et de fin des évènements doit suivre le format
Datetime pour stocker la date et l'heure de l'event.

● Tous les formulaires doivent présenter leurs messages d'erreur.

● Le design doit être fidèle au Figma fourni (**EventPlanner** adapté au
projet).

**[Lien Figma :]{.underline}**
https://www.figma.com/design/kMI3xEuMDQIishN1v9WhY2/Syst%C3%A8me-de-gestion-d%E2%80%99%C3%A9v%C3%A9nements?node-id=5-1133&p=f&t=25AFiK4mSbT69PGV-0
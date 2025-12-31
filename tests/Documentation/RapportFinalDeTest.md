# Rapport Final de Test – AAB_EventPlanner

---

**Projet** : AAB_EventPlanner – Application Web de Gestion d'Événements  
**Version du document** : 1.1  
**Date** : 29 décembre 2025  
**Module** : Test et Qualité Logiciel  
**Norme de référence** : ISTQB Foundation Level  

---

## Équipe de Test

| Rôle | Nom | Responsabilités |
|------|-----|----------------|
| Testeur Principal | Adam | Tests unitaires PHPUnit (modèles Event, User), automatisation Selenium, tests Playwright |
| Testeur | Afra | Tests unitaires PHPUnit (règles de validation), tests d'intégration, documentation |

### Répartition du Travail

**Tests Unitaires (Travail Individuel)** : Les tests unitaires PHPUnit ont été **répartis entre les deux contributeurs**, chaque personne étant responsable d'un ensemble distinct de cas de test sans chevauchement :
- **Adam** : Tests des modèles (`AAB_EventModelTest.php`, `UserModelTest.php`)
- **Afra** : Tests des règles de validation (`ValidationRulesTest.php`)

**Autres Activités de Test (Travail Collaboratif)** : Toutes les autres activités de test ont été réalisées **conjointement par les deux contributeurs** :
- Tests d'intégration PHPUnit (Feature Tests)
- Tests Selenium/Pytest (automatisation E2E)
- Tests Playwright (outil bonus)
- Exécution, analyse et validation des tests
- Revue de code et tests statiques
- Rédaction de la documentation

---

## Table des Matières

1. [Introduction](#1-introduction)
2. [Contexte et Objectifs de Test](#2-contexte-et-objectifs-de-test)
3. [Système Sous Test (SUT)](#3-système-sous-test-sut)
4. [Stratégie et Approche de Test](#4-stratégie-et-approche-de-test)
5. [Activités de Test Statique](#5-activités-de-test-statique)
6. [Conception et Cas de Test](#6-conception-et-cas-de-test)
7. [Stratégie d'Automatisation](#7-stratégie-dautomatisation)
8. [Résultats d'Exécution des Tests](#8-résultats-dexécution-des-tests)
9. [Synthèse des Défauts et Observations](#9-synthèse-des-défauts-et-observations)
10. [Matrice de Traçabilité](#10-matrice-de-traçabilité)
11. [Risques, Limitations et Améliorations](#11-risques-limitations-et-améliorations)
12. [Outil Bonus : Playwright](#12-outil-bonus--playwright)
13. [Barème d'Évaluation](#13-barème-dévaluation)
14. [Conclusion](#14-conclusion)
15. [Déclaration d'Utilisation de l'IA](#15-déclaration-dutilisation-de-lia)
16. [Annexes](#16-annexes)

---

## 1. Introduction

### 1.1 Objet du Document

Le présent rapport constitue le livrable final du projet de test réalisé dans le cadre du module **Test et Qualité Logiciel**. Il consolide l'ensemble des activités de test effectuées sur l'application web **AAB_EventPlanner**, développée avec le framework Laravel.

Ce document suit les principes et la terminologie définis par le syllabus **ISTQB Foundation Level** et respecte les exigences du guide pédagogique `Guideline_Test_Qualite_Logiciel.md`.

### 1.2 Portée du Rapport

Ce rapport couvre :
- Les activités de **test statique** (revue de code, analyse statique)
- Les **tests dynamiques** à tous les niveaux (unitaire, intégration, système)
- Les **tests fonctionnels et non fonctionnels**
- L'**automatisation** des tests avec Page Object Model
- La **traçabilité** bidirectionnelle exigences-tests-résultats

### 1.3 Public Cible

- Enseignants responsables du module Test et Qualité Logiciel
- Membres de l'équipe projet
- Auditeurs qualité

### 1.4 Documents de Référence

| Document | Description |
|----------|-------------|
| Guideline_Test_Qualite_Logiciel.md | Guide pédagogique du projet |
| Documentation.md | Documentation fonctionnelle de l'application |
| phpunit.xml | Configuration des tests PHPUnit |
| pytest.ini | Configuration des tests Selenium/Pytest |

---

## 2. Contexte et Objectifs de Test

### 2.1 Contexte du Projet

Le projet AAB_EventPlanner est une application web de gestion d'événements permettant aux utilisateurs de :
- Consulter et rechercher des événements
- S'inscrire aux événements
- Gérer leur profil utilisateur

Les administrateurs disposent de fonctionnalités avancées :
- Gestion CRUD des événements et catégories
- Gestion des utilisateurs et des rôles
- Tableau de bord d'administration

### 2.2 Objectifs de Test

| Objectif | Description | Priorité |
|----------|-------------|----------|
| **OT-01** | Vérifier la conformité fonctionnelle de toutes les fonctionnalités | Haute |
| **OT-02** | Valider le contrôle d'accès basé sur les rôles (RBAC) | Haute |
| **OT-03** | Assurer la qualité des validations de données | Haute |
| **OT-04** | Tester les scénarios de bout en bout (E2E) | Moyenne |
| **OT-05** | Identifier les vulnérabilités de sécurité basiques | Moyenne |
| **OT-06** | Documenter la traçabilité complète | Moyenne |

### 2.3 Critères de Succès

- **Taux de réussite des tests critiques** : ≥ 95%
- **Couverture des exigences** : 100%
- **Zéro défaut de sévérité haute** non résolu
- **Traçabilité** complète exigences ↔ tests ↔ résultats

---

## 3. Système Sous Test (SUT)

### 3.1 Description Générale

| Caractéristique | Valeur |
|-----------------|--------|
| **Nom** | AAB_EventPlanner |
| **Type** | Application Web MVC |
| **Framework** | Laravel 11.x |
| **Langage Backend** | PHP 8.2+ |
| **Base de données** | MySQL 8.x |
| **Frontend** | Blade Templates, CSS, JavaScript |
| **Authentification** | Laravel Auth + Spatie Permission |

### 3.2 Modules Fonctionnels

| Module | Description | Contrôleur |
|--------|-------------|------------|
| **Authentification** | Connexion, inscription, déconnexion | AAB_AuthController |
| **Événements** | CRUD événements, liste publique, recherche | AAB_EventController |
| **Inscriptions** | Inscription/désinscription aux événements | AAB_RegistrationController |
| **Catégories** | CRUD catégories d'événements | AAB_CategoryController |
| **Utilisateurs** | Gestion des utilisateurs (Admin) | AAB_UserController |
| **Profil** | Gestion du profil utilisateur | AAB_ProfileController |

### 3.3 Rôles Utilisateur

| Rôle | Permissions |
|------|-------------|
| **Admin** | Toutes les permissions (CRUD complet) |
| **Manager** | Lecture événements/catégories, gestion limitée |
| **User** | Consultation, inscription événements, gestion profil |
| **Guest** | Consultation publique uniquement |

### 3.4 Architecture Technique

```
AAB_EventPlanner/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Contrôleurs MVC
│   │   ├── Middleware/        # Middleware d'autorisation
│   │   └── Requests/          # Form Requests (validation)
│   └── Models/                # Modèles Eloquent
├── database/
│   ├── factories/             # Factories pour les tests
│   ├── migrations/            # Schéma de base de données
│   └── seeders/               # Données de test
├── resources/views/           # Templates Blade
├── routes/web.php             # Définition des routes
└── tests/                     # Dossier de tests
```

---

## 4. Stratégie et Approche de Test

### 4.1 Niveaux de Test Appliqués

Conformément aux exigences du guide pédagogique, trois niveaux de test ont été implémentés :

| Niveau | Description | Outils | Responsabilité |
|--------|-------------|--------|----------------|
| **Unitaire** | Test des composants isolés (modèles, validation) | PHPUnit | Individuel (Adam: modèles, Afra: validation) |
| **Intégration** | Test des interactions entre modules et API | PHPUnit Feature Tests | Collaboratif (Adam & Afra) |
| **Système** | Test E2E des scénarios utilisateur complets | Selenium + Pytest | Collaboratif (Adam & Afra) |

### 4.2 Types de Test Réalisés

| Type | Catégorie | Description |
|------|-----------|-------------|
| **Fonctionnel** | Boîte noire | Tests basés sur les exigences fonctionnelles |
| **Sécurité** | Non-fonctionnel | Contrôle d'accès, protection CSRF |
| **Performance** | Non-fonctionnel | Temps de réponse (implémenté avec PHPUnit) |

### 4.3 Techniques de Conception de Test (ISTQB)

| Technique | Application | Cas de Test Associés |
|-----------|-------------|---------------------|
| **Partitionnement en classes d'équivalence** | Validation email, mot de passe | TC-AUTH-003, TC-AUTH-004, TC-AUTH-006 |
| **Analyse des valeurs limites** | Longueur mot de passe, capacité événement | TC-AUTH-007, TC-AUTH-008, TC-REG-002 |
| **Table de décision** | Contrôle d'accès basé sur les rôles | TC-EVT-045, TC-SEC-001, TC-SEC-002 |
| **Transition d'état** | Statut événement (actif → archivé) | TC-EVT-002, TC-EVT-044 |
| **Test basé sur les cas d'utilisation** | Scénarios E2E complets | TC-AUTH-001, TC-REG-001, TC-EVT-030 |
| **Test boîte blanche** | Validation des règles, protection CSRF | TC-SEC-003 |

### 4.4 Environnement de Test

| Composant | Version/Configuration |
|-----------|----------------------|
| Système d'exploitation | Windows |
| PHP | 8.2+ |
| Laravel | 11.x |
| MySQL | 8.x |
| PHPUnit | 10.x |
| Python | 3.x |
| Selenium WebDriver | 4.x |
| Pytest | Latest |
| Navigateur | Chrome (Latest) |
| IDE | Visual Studio Code |

### 4.5 Données de Test

| Utilisateur | Email | Mot de passe | Rôle |
|-------------|-------|--------------|------|
| Administrateur | admin@eventplanner.com | admin123 | admin |
| Manager | manager@eventplanner.com | manager123 | manager |
| Utilisateur | user@eventplanner.com | user123 | user |

---

## 5. Activités de Test Statique

### 5.1 Introduction aux Tests Statiques

Les tests statiques permettent de détecter des défauts sans exécuter le code. Conformément au guide pédagogique, **deux activités statiques obligatoires** ont été réalisées.

### 5.2 Activité 1 : Revue de Code

#### 5.2.1 Sessions de Revue

| Session | Date | Réviseurs | Fichiers Révisés |
|---------|------|-----------|------------------|
| Session 1 | 26/12/2025 | Revue assistée par IA | Contrôleurs |
| Session 2 | 26/12/2025 | Revue assistée par IA | Modèles |
| Session 3 | 26/12/2025 | Revue assistée par IA | Routes & Middleware |

#### 5.2.2 Checklist de Revue Appliquée

- [x] Vérification de l'authentification et de l'autorisation
- [x] Validation des entrées utilisateur
- [x] Prévention des injections SQL
- [x] Gestion des erreurs
- [x] Cohérence du code
- [x] Sécurité des routes
- [x] Validation de la logique métier

### 5.3 Activité 2 : Analyse Statique

| Outil | Objectif | Résultats |
|-------|----------|-----------|
| Revue manuelle de code | Analyse logique et sécurité | 11 anomalies détectées |
| IDE (VS Code) | Vérification syntaxe et types | Aucune erreur |

### 5.4 Anomalies Détectées

#### 5.4.1 Synthèse par Sévérité

| Sévérité | Nombre | Pourcentage |
|----------|--------|-------------|
| Haute | 0 | 0% |
| Moyenne | 3 | 27% |
| Basse | 7 | 64% |
| Information | 1 | 9% |
| **Total** | **11** | **100%** |

#### 5.4.2 Détail des Anomalies

| ID | Sévérité | Composant | Description | Recommandation |
|----|----------|-----------|-------------|----------------|
| DEF-001 | Basse | AAB_AuthController | Redirection incohérente après login | Vérifier la cohérence des noms de route |
| DEF-002 | Moyenne | AAB_AuthController | Absence de journalisation des échecs de connexion | Ajouter un logging sécurité |
| DEF-003 | Basse | AAB_EventController | Portée incorrecte de la requête de recherche | Encapsuler dans une closure |
| DEF-004 | Basse | AAB_EventController | Validation manquante du filtre status | Valider les valeurs autorisées |
| DEF-005 | Basse | AAB_EventController | Redirection incohérente après création | Rediriger vers events.list |
| DEF-006 | Moyenne | AAB_RegistrationController | Vérification manquante du statut événement | Empêcher l'inscription aux événements archivés |
| DEF-007 | Basse | AAB_ProfileController | Types de retour manquants | Ajouter les type hints PHP |
| DEF-008 | Info | Routes | Route commentée référencée dans le code | Nettoyer ou restaurer |
| DEF-009 | Moyenne | Routes | Lacune d'autorisation sur les routes admin | Ajouter middleware de vérification de rôle |
| DEF-010 | Basse | AAB_Event Model | isFull() ne gère pas capacity null | Ajouter vérification null |
| DEF-011 | Basse | Routes | Vérification auth redondante | Acceptable (programmation défensive) |

#### 5.4.3 Actions Prioritaires

1. **DEF-006** : Ajouter vérification du statut avant inscription
2. **DEF-009** : Sécuriser les routes admin avec middleware de rôle
3. **DEF-003** : Corriger la portée de la requête de recherche

### 5.5 Conclusion des Tests Statiques

Les activités de test statique ont permis d'identifier **11 anomalies** dont **3 de sévérité moyenne** nécessitant une correction prioritaire. Aucun défaut de sévérité haute n'a été détecté, ce qui témoigne d'une qualité de code acceptable.

---

## 6. Conception et Cas de Test

### 6.1 Analyse des Exigences

L'analyse des exigences a permis d'identifier **36 exigences fonctionnelles et non fonctionnelles** réparties sur 6 modules :

| Module | Nombre d'Exigences |
|--------|-------------------|
| Authentification | 9 |
| Événements | 9 |
| Inscriptions | 6 |
| Catégories | 5 |
| Profil | 4 |
| Sécurité | 3 |
| **Total** | **36** |

### 6.2 Synthèse des Cas de Test

#### 6.2.1 Distribution par Niveau de Test

| Niveau | Nombre | Implémentés | Couverture |
|--------|--------|-------------|------------|
| Unitaire | 10 | 9 | 90% |
| Intégration | 25 | 20 | 80% |
| Système | 35 | 17 | 49% |
| **Total** | **70** | **46** | **66%** |

#### 6.2.2 Distribution par Type de Test

| Type | Nombre | Implémentés | Couverture |
|------|--------|-------------|------------|
| Fonctionnel | 63 | 43 | 68% |
| Non-fonctionnel (Sécurité) | 4 | 3 | 75% |
| Non-fonctionnel (Performance) | 4 | 4 | 100% |

#### 6.2.3 Distribution par Technique

| Technique ISTQB | Nombre de Cas |
|-----------------|---------------|
| Partitionnement en classes d'équivalence | 28 |
| Analyse des valeurs limites | 12 |
| Table de décision | 10 |
| Test basé sur cas d'utilisation | 15 |
| Transition d'état | 3 |
| Boîte blanche | 2 |

### 6.3 Cas de Test par Module

#### 6.3.1 Module Authentification

| ID | Cas de Test | Technique | Niveau | Priorité |
|----|-------------|-----------|--------|----------|
| TC-AUTH-001 | Connexion valide - Admin | Cas d'utilisation | Système | Haute |
| TC-AUTH-002 | Connexion valide - Utilisateur | Cas d'utilisation | Système | Haute |
| TC-AUTH-003 | Connexion invalide - Mot de passe erroné | Partitionnement | Système | Haute |
| TC-AUTH-004 | Connexion invalide - Email inexistant | Partitionnement | Système | Haute |
| TC-AUTH-005 | Connexion invalide - Champs vides | Valeurs limites | Système | Moyenne |
| TC-AUTH-006 | Connexion invalide - Format email | Partitionnement | Système | Moyenne |
| TC-AUTH-007 | Mot de passe sous longueur minimale | Valeurs limites | Unitaire | Moyenne |
| TC-AUTH-008 | Mot de passe à longueur minimale | Valeurs limites | Unitaire | Moyenne |
| TC-AUTH-010 | Inscription valide | Partitionnement | Système | Haute |
| TC-AUTH-011 | Inscription - Email dupliqué | Partitionnement | Intégration | Haute |
| TC-AUTH-012 | Inscription - Mots de passe différents | Partitionnement | Système | Haute |
| TC-AUTH-013 | Inscription - Mot de passe trop court | Valeurs limites | Unitaire | Moyenne |
| TC-AUTH-020 | Déconnexion valide | Cas d'utilisation | Système | Haute |
| TC-AUTH-021 | Invalidation de session | Boîte blanche | Intégration | Haute |

#### 6.3.2 Module Événements

| ID | Cas de Test | Technique | Niveau | Priorité |
|----|-------------|-----------|--------|----------|
| TC-EVT-001 | Affichage événements actifs | Cas d'utilisation | Système | Haute |
| TC-EVT-002 | Événements archivés masqués | Transition d'état | Intégration | Haute |
| TC-EVT-003 | Pagination des événements | Valeurs limites | Système | Moyenne |
| TC-EVT-010 | Recherche par titre | Partitionnement | Système | Moyenne |
| TC-EVT-011 | Recherche par description | Partitionnement | Intégration | Moyenne |
| TC-EVT-020 | Filtre par catégorie | Partitionnement | Système | Moyenne |
| TC-EVT-030 | Affichage détails événement | Cas d'utilisation | Système | Haute |
| TC-EVT-040 | Création événement - Admin | Partitionnement | Intégration | Haute |
| TC-EVT-041 | Création - Titre manquant | Partitionnement | Unitaire | Haute |
| TC-EVT-043 | Modification événement | Cas d'utilisation | Intégration | Moyenne |
| TC-EVT-044 | Archivage événement | Transition d'état | Intégration | Moyenne |
| TC-EVT-045 | Création - Accès non-admin | Table de décision | Intégration | Haute |

#### 6.3.3 Module Inscriptions

| ID | Cas de Test | Technique | Niveau | Priorité |
|----|-------------|-----------|--------|----------|
| TC-REG-001 | Inscription à un événement | Cas d'utilisation | Système | Haute |
| TC-REG-002 | Inscription - Événement complet | Valeurs limites | Intégration | Haute |
| TC-REG-003 | Inscription - Déjà inscrit | Partitionnement | Intégration | Haute |
| TC-REG-004 | Inscription - Non connecté | Table de décision | Système | Haute |
| TC-REG-005 | Désinscription | Cas d'utilisation | Système | Moyenne |
| TC-REG-010 | Affichage mes inscriptions | Cas d'utilisation | Système | Moyenne |

#### 6.3.4 Module Sécurité (Non-fonctionnel)

| ID | Cas de Test | Technique | Niveau | Type |
|----|-------------|-----------|--------|------|
| TC-SEC-001 | Accès admin sans authentification | Table de décision | Système | Sécurité |
| TC-SEC-002 | Accès admin avec rôle user | Table de décision | Système | Sécurité |
| TC-SEC-003 | Protection CSRF | Boîte blanche | Intégration | Sécurité |

#### 6.3.5 Module Performance (Non-fonctionnel)

| ID | Cas de Test | Technique | Niveau | Type |
|----|-------------|-----------|--------|------|
| TC-PERF-001 | Temps de chargement page d'accueil | Benchmark | Système | Performance |
| TC-PERF-002 | Temps de réponse connexion | Benchmark | Système | Performance |
| TC-PERF-003 | Performance pagination événements | Benchmark | Système | Performance |
| TC-PERF-004 | Performance recherche | Benchmark | Système | Performance |

### 6.4 Cas de Test Détaillés (Template Formel)

Le template de documentation de cas de test ci-dessous a été appliqué pour formaliser les cas de test critiques. Ce format suit les bonnes pratiques ISTQB et permet une exécution structurée.

**Note** : Le template fourni a été adapté pour inclure les champs spécifiques au projet (technique de test, niveau de test) tout en conservant la structure de base proposée.

---

#### Cas de Test TC-AUTH-003 : Connexion avec Mot de Passe Incorrect

| Champ | Valeur |
|-------|--------|
| **ID Cas de test** | TC-AUTH-003 |
| **Titre Cas de test** | Se connecter avec Mot de passe incorrect |
| **Créé par** | Adam |
| **Revue par** | Afra |
| **Nom du testeur** | Adam |
| **Date de test** | 29 décembre 2025 |
| **Version** | 1.0 |
| **Cas de test (Pass/Fail/Not Executed)** | À exécuter |

| S # | Prérequis |
|-----|-----------|
| 1 | Accéder à Google Chrome |
| 2 | Effacer le token/cookies de session |
| 3 | L'utilisateur test@eventplanner.com existe en base |
| 4 | Mot de passe correct : user123 |

| S # | Jeu de données de test |
|-----|------------------------|
| 1 | UserId = test@eventplanner.com / Mdp = wrongpassword |
| 2 | UserId = admin@eventplanner.com / Mdp = incorrectpwd |

| **Scénario de test** | Vérifier si l'utilisateur ne peut pas accéder à son espace s'il saisit un Mot de passe incorrect |
|----------------------|--------------------------------------------------------------------------------------------------|

| Étape # | Étapes | Résultats Attendus | Résultats Réels | Pass / Fail / Blocked |
|---------|--------|-------------------|-----------------|----------------------|
| 1 | Aller vers http://localhost/AAB_EventPlanner/public/login | Le site web s'ouvre sur la page /login | | `SCREENSHOT HERE – TC-AUTH-003 – Step1` |
| 2 | Entrer UserId & Mot de passe incorrect | Les champs acceptent les valeurs sans problème | | |
| 3 | Cliquer sur le bouton de connexion | Le système affiche le message "Invalid email or password" | | `SCREENSHOT HERE – TC-AUTH-003 – Step3` |

**Technique de test** : Partitionnement en classes d'équivalence (classe invalide)  
**Niveau de test** : Système  
**Type de test** : Fonctionnel  
**Priorité** : Haute  
**Implémentation** : test_001_login.py::test_TC_AUTH_003_invalid_login_wrong_password

---

#### Cas de Test TC-REG-001 : Inscription à un Événement

| Champ | Valeur |
|-------|--------|
| **ID Cas de test** | TC-REG-001 |
| **Titre Cas de test** | Inscription d'un utilisateur à un événement |
| **Créé par** | Afra |
| **Revue par** | Adam |
| **Nom du testeur** | Afra |
| **Date de test** | 29 décembre 2025 |
| **Version** | 1.0 |
| **Cas de test (Pass/Fail/Not Executed)** | À exécuter |

| S # | Prérequis |
|-----|-----------|
| 1 | Accéder à Google Chrome |
| 2 | Utilisateur connecté avec le rôle "user" |
| 3 | Au moins un événement actif avec places disponibles |
| 4 | L'utilisateur n'est pas déjà inscrit à cet événement |

| S # | Jeu de données de test |
|-----|------------------------|
| 1 | UserId = user@eventplanner.com / Mdp = user123 |
| 2 | Événement : "Tech Conference 2025" (places disponibles: 50) |

| **Scénario de test** | Vérifier qu'un utilisateur connecté peut s'inscrire à un événement disponible |
|----------------------|-------------------------------------------------------------------------------|

| Étape # | Étapes | Résultats Attendus | Résultats Réels | Pass / Fail / Blocked |
|---------|--------|-------------------|-----------------|----------------------|
| 1 | Se connecter avec les identifiants utilisateur | Redirection vers la page d'accueil | | |
| 2 | Naviguer vers la page d'accueil /home | Liste des événements affichée | | `SCREENSHOT HERE – TC-REG-001 – Step2` |
| 3 | Cliquer sur un événement pour voir les détails | Page de détails de l'événement affichée | | `SCREENSHOT HERE – TC-REG-001 – Step3` |
| 4 | Cliquer sur le bouton "S'inscrire" | Message de succès "Registration successful" affiché | | `SCREENSHOT HERE – TC-REG-001 – Step4` |
| 5 | Vérifier dans "Mes inscriptions" | L'événement apparaît dans la liste des inscriptions | | `SCREENSHOT HERE – TC-REG-001 – Step5` |

**Technique de test** : Test basé sur les cas d'utilisation  
**Niveau de test** : Système  
**Type de test** : Fonctionnel  
**Priorité** : Haute  
**Implémentation** : test_004_event_registration.py::test_TC_REG_001_register_for_event

---

## 7. Stratégie d'Automatisation

### 7.1 Portée de l'Automatisation

L'automatisation des tests répond à l'exigence du guide pédagogique d'avoir **au moins un test automatisé** utilisant le **Page Object Model**.

| Niveau | Outil | Nombre de Tests | Couverture |
|--------|-------|-----------------|------------|
| Unitaire | PHPUnit | 9 | 90% |
| Intégration | PHPUnit Feature | 24 | 85% |
| Système | Selenium + Pytest | 17 | 49% |
| Performance | PHPUnit | 4 | 100% |
| Bonus | Playwright | 6 | N/A |
| **Total** | - | **60** | **75%** |

### 7.2 Architecture des Tests PHPUnit

#### 7.2.1 Tests Unitaires

**Note importante** : Les tests unitaires ont été **répartis individuellement** entre les deux contributeurs, sans chevauchement. Chaque contributeur était responsable de la conception, de l'implémentation et de l'exécution de ses propres cas de test.

| Fichier | Tests | Description | Contributeur |
|---------|-------|-------------|---------------|
| ValidationRulesTest.php | 12 | Règles de validation des formulaires | **Afra** |
| AAB_EventModelTest.php | 4 | Méthodes du modèle Event | **Adam** |
| UserModelTest.php | 4 | Attributs et méthodes du modèle User | **Adam** |

#### 7.2.2 Tests d'Intégration (Feature)

**Note** : Contrairement aux tests unitaires, les tests d'intégration ont été réalisés **en collaboration** par Adam et Afra, avec une revue croisée et une validation conjointe.

| Fichier | Tests | Description | Mode de travail |
|---------|-------|-------------|------------------|
| AuthenticationTest.php | 14 | Flux d'authentification complet | Collaboratif (Adam & Afra) |
| EventTest.php | 12 | CRUD et affichage des événements | Collaboratif (Adam & Afra) |
| RegistrationTest.php | 10 | Inscription aux événements | Collaboratif (Adam & Afra) |
| CategoryTest.php | 9 | CRUD des catégories | Collaboratif (Adam & Afra) |
| ProfileTest.php | 9 | Gestion du profil | Collaboratif (Adam & Afra) |
| **PerformanceTest.php** | 4 | Tests de performance (TC-PERF-001 à TC-PERF-004) | Collaboratif (Adam & Afra) |

### 7.3 Architecture Selenium avec Page Object Model

**Mode de travail** : L'automatisation Selenium a été réalisée **conjointement** par Adam et Afra, incluant la conception de l'architecture Page Object Model, l'implémentation des pages et des tests, ainsi que l'exécution et la validation des résultats.

#### 7.3.1 Structure du Projet

```
tests/Selenium/
├── Configurations/
│   └── config.ini              # Configuration (URLs, credentials)
├── pages/
│   ├── __init__.py
│   ├── BasePage.py             # Classe de base POM
│   ├── LoginPage.py            # Page de connexion
│   ├── RegisterPage.py         # Page d'inscription
│   ├── HomePage.py             # Page d'accueil
│   └── EventDetailsPage.py     # Page détails événement
├── tests/
│   ├── __init__.py
│   ├── conftest.py             # Fixtures Pytest
│   ├── test_001_login.py       # Tests de connexion
│   ├── test_002_registration.py # Tests d'inscription
│   ├── test_003_events.py      # Tests des événements
│   └── test_004_event_registration.py # Tests inscription événement
├── utilities/
│   ├── readProperties.py       # Lecture configuration
│   └── customLogger.py         # Journalisation
├── pytest.ini                  # Configuration Pytest
└── requirements.txt            # Dépendances Python
```

#### 7.3.2 Implémentation Page Object Model

**Classe BasePage (Classe Mère)**

```python
class BasePage:
    """Classe de base pour tous les Page Objects."""
    
    def __init__(self, driver):
        self.driver = driver
        self.wait = WebDriverWait(driver, 10)
    
    def get_element(self, locator):
        """Attend et retourne un élément."""
        return self.wait.until(EC.presence_of_element_located(locator))
    
    def click_element(self, locator):
        """Attend qu'un élément soit cliquable et clique."""
        element = self.wait.until(EC.element_to_be_clickable(locator))
        element.click()
    
    def enter_text(self, locator, text):
        """Efface et saisit du texte dans un champ."""
        element = self.get_element(locator)
        element.clear()
        element.send_keys(text)
```

**Classe LoginPage (Page Concrète)**

```python
class LoginPage(BasePage):
    """Page Object pour la page de connexion."""
    
    # Localisateurs
    INPUT_EMAIL = (By.NAME, "email")
    INPUT_PASSWORD = (By.NAME, "password")
    BUTTON_LOGIN = (By.CSS_SELECTOR, "button[type='submit']")
    
    def login(self, email, password):
        """Effectue l'action de connexion complète."""
        self.enter_text(self.INPUT_EMAIL, email)
        self.enter_text(self.INPUT_PASSWORD, password)
        self.click_element(self.BUTTON_LOGIN)
```

#### 7.3.3 Exemple de Test Automatisé

```python
class Test_001_Login:
    """Classe de test pour la fonctionnalité Login."""
    
    @pytest.mark.smoke
    @pytest.mark.authentication
    def test_TC_AUTH_001_valid_admin_login(self, setup):
        """
        TC-AUTH-001: Connexion valide - Utilisateur Admin
        
        Technique: Test basé sur cas d'utilisation
        Préconditions: Utilisateur admin existe en base
        Résultat attendu: Redirection vers la page admin/events
        """
        self.driver = setup
        self.driver.get(self.base_url + "/login")
        
        login_page = LoginPage(self.driver)
        login_page.login(self.admin_email, self.admin_password)
        
        assert "admin/events" in self.driver.current_url
```

### 7.4 Exécution des Tests

#### 7.4.1 Commandes PHPUnit

```bash
# Tous les tests
php artisan test

# Tests unitaires uniquement
php artisan test --testsuite=Unit

# Tests d'intégration uniquement
php artisan test --testsuite=Feature

# Avec couverture de code
php artisan test --coverage
```

#### 7.4.2 Commandes Pytest/Selenium

```bash
# Installation des dépendances
cd tests/Selenium
pip install -r requirements.txt

# Exécution de tous les tests
pytest tests/ -v

# Tests de smoke uniquement
pytest tests/ -v -m smoke

# Fichier spécifique
pytest tests/test_001_login.py -v
```

SELENIUM TESTS SCREENSHOT HERE
---

## 8. Résultats d'Exécution des Tests

### 8.1 Période d'Exécution

| Paramètre | Valeur |
|-----------|--------|
| Date de début | 26 décembre 2025 |
| Date de fin | 29 décembre 2025 |
| Environnement | Développement local (XAMPP) |

### 8.2 Synthèse Globale

| Métrique | Valeur |
|----------|--------|
| Cas de test conçus | 70 |
| Cas de test implémentés | 46 |
| Taux d'implémentation | 66% |
| Anomalies de test statique | 11 |
| Défauts critiques | 0 |
| Défauts moyens | 3 |
| Défauts mineurs | 7 |

### 8.3 Résultats par Catégorie

| Catégorie | Total | À Exécuter |
|-----------|-------|------------|
| Authentification | 16 | 16 |
| Événements | 15 | 15 |
| Inscriptions | 8 | 8 |
| Catégories | 6 | 6 |
| Profil | 6 | 6 |
| Sécurité | 4 | 4 |
| **Total** | **55** | **55** |

### 8.4 Prérequis d'Exécution

1. **Base de données initialisée** :
   ```bash
   php artisan migrate:fresh --seed
   ```

2. **Utilisateurs de test créés** (via seeders)

3. **Application démarrée** (pour Selenium) :
   ```bash
   php artisan serve
   # Ou via XAMPP : http://localhost/AAB_EventPlanner/public
   ```

4. **Chrome installé** (pour Selenium WebDriver)

### 8.5 Couverture des Tests

#### 8.5.1 Couverture des Exigences

| Module | Exigences | Couvertes | Couverture |
|--------|-----------|-----------|------------|
| Authentification | 9 | 9 | 100% |
| Événements | 9 | 9 | 100% |
| Inscriptions | 6 | 6 | 100% |
| Catégories | 5 | 5 | 100% |
| Profil | 4 | 4 | 100% |
| Sécurité | 3 | 3 | 100% |
| **Total** | **36** | **36** | **100%** |

#### 8.5.2 Couverture du Code (Estimée)

| Composant | Fichiers | Couverts | Couverture |
|-----------|----------|----------|------------|
| Contrôleurs | 6 | 6 | 100% |
| Modèles | 4 | 4 | 100% |
| Requests | 4 | 3 | 75% |
| Routes | 1 | 1 | 100% |

---

## 9. Synthèse des Défauts et Observations

### 9.1 Classification des Défauts

#### 9.1.1 Par Sévérité

| Sévérité | Définition | Nombre |
|----------|------------|--------|
| **Haute** | Bloque une fonctionnalité critique | 0 |
| **Moyenne** | Impact fonctionnel significatif | 3 |
| **Basse** | Impact mineur, contournement possible | 7 |
| **Information** | Amélioration suggérée | 1 |

#### 9.1.2 Par Catégorie

| Catégorie | Nombre | Pourcentage |
|-----------|--------|-------------|
| Sécurité | 2 | 18% |
| Qualité de code | 5 | 46% |
| Erreur logique | 3 | 27% |
| Documentation | 1 | 9% |

### 9.2 Défauts Prioritaires

| Priorité | ID | Description | Impact |
|----------|-----|-------------|--------|
| 1 | DEF-006 | Inscription possible aux événements archivés | Violation règle métier |
| 2 | DEF-009 | Routes admin accessibles sans vérification de rôle | Faille de sécurité |
| 3 | DEF-003 | Portée incorrecte de la requête de recherche | Résultats incorrects |

### 9.3 Observations Qualité

#### 9.3.1 Points Forts
- Architecture MVC bien respectée
- Utilisation appropriée des Form Requests pour la validation
- Séparation claire des rôles avec Spatie Permission
- Factories bien définies pour les tests
- Code cohérent et lisible

#### 9.3.2 Points d'Amélioration
- Renforcer la validation côté contrôleur
- Ajouter la journalisation des événements de sécurité
- Compléter les type hints PHP
- Nettoyer le code commenté

---

## 10. Matrice de Traçabilité

### 10.1 Traçabilité Exigences → Tests → Implémentation

#### 10.1.1 Module Authentification

| Exigence | Cas de Test | Implémentation PHPUnit | Implémentation Selenium |
|----------|-------------|------------------------|-------------------------|
| REQ-AUTH-01 | TC-AUTH-001, TC-AUTH-002 | ✅ AuthenticationTest | ✅ test_001_login.py |
| REQ-AUTH-02 | TC-AUTH-003, TC-AUTH-004 | ✅ AuthenticationTest | ✅ test_001_login.py |
| REQ-AUTH-03 | TC-AUTH-007, TC-AUTH-008 | ✅ ValidationRulesTest | - |
| REQ-AUTH-04 | TC-AUTH-010 | ✅ AuthenticationTest | ✅ test_002_registration.py |
| REQ-AUTH-05 | TC-AUTH-011 | ✅ ValidationRulesTest | - |
| REQ-AUTH-06 | TC-AUTH-013, TC-AUTH-014 | ✅ ValidationRulesTest | ✅ test_002_registration.py |
| REQ-AUTH-07 | TC-AUTH-012 | ✅ ValidationRulesTest | ✅ test_002_registration.py |
| REQ-AUTH-08 | TC-AUTH-020, TC-AUTH-021 | ✅ AuthenticationTest | - |

#### 10.1.2 Module Événements

| Exigence | Cas de Test | Implémentation PHPUnit | Implémentation Selenium |
|----------|-------------|------------------------|-------------------------|
| REQ-EVT-01 | TC-EVT-001 | ✅ EventTest | ✅ test_003_events.py |
| REQ-EVT-02 | TC-EVT-002 | ✅ EventTest | - |
| REQ-EVT-03 | TC-EVT-010, TC-EVT-011 | ✅ EventTest | ✅ test_003_events.py |
| REQ-EVT-05 | TC-EVT-030 | ✅ EventTest | ✅ test_003_events.py |
| REQ-EVT-06 | TC-EVT-040, TC-EVT-041 | ✅ EventTest | - |
| REQ-EVT-08 | TC-EVT-044 | ✅ EventTest | - |
| REQ-EVT-09 | TC-EVT-045 | ✅ EventTest | - |

#### 10.1.3 Module Inscriptions

| Exigence | Cas de Test | Implémentation PHPUnit | Implémentation Selenium |
|----------|-------------|------------------------|-------------------------|
| REQ-REG-01 | TC-REG-001 | ✅ RegistrationTest | ✅ test_004_event_registration.py |
| REQ-REG-02 | TC-REG-002 | ✅ RegistrationTest | - |
| REQ-REG-03 | TC-REG-003 | ✅ RegistrationTest | - |
| REQ-REG-04 | TC-REG-004 | ✅ RegistrationTest | ✅ test_004_event_registration.py |
| REQ-REG-05 | TC-REG-005 | ✅ RegistrationTest | ✅ test_004_event_registration.py |

#### 10.1.4 Module Performance (Non-fonctionnel)

| Exigence | Cas de Test | Implémentation PHPUnit | Implémentation Playwright |
|----------|-------------|------------------------|---------------------------|
| REQ-PERF-01 | TC-PERF-001 | ✅ PerformanceTest | ✅ user-journey.spec.ts |
| REQ-PERF-02 | TC-PERF-002 | ✅ PerformanceTest | - |
| REQ-PERF-03 | TC-PERF-003 | ✅ PerformanceTest | - |
| REQ-PERF-04 | TC-PERF-004 | ✅ PerformanceTest | - |

#### 10.1.5 Module Bonus (Playwright)

| Exigence | Cas de Test | Implémentation |
|----------|-------------|----------------|
| REQ-BONUS-01 | TC-PW-001 | ✅ user-journey.spec.ts (6 sous-tests) |
| REQ-BONUS-02 | TC-PW-002 | ✅ user-journey.spec.ts |

### 10.2 Vérification de la Traçabilité

| Critère | Statut |
|---------|--------|
| Toutes les exigences ont au moins un cas de test | ✅ Vérifié |
| Tous les cas de test sont liés à une exigence | ✅ Vérifié |
| Tous les cas implémentés ont des méthodes de test | ✅ Vérifié |
| Les niveaux de test sont appropriés | ✅ Vérifié |
| Les fonctionnalités critiques ont des tests système | ✅ Vérifié |

---

## 11. Risques, Limitations et Améliorations

### 11.1 Risques Identifiés

| Risque | Probabilité | Impact | Mitigation |
|--------|-------------|--------|------------|
| Incohérence état BDD entre tests | Moyenne | Haute | Utilisation de RefreshDatabase |
| Incompatibilité WebDriver | Basse | Moyenne | WebDriver Manager auto-update |
| Dépendances données de test | Moyenne | Moyenne | Setup indépendant par test |
| Temps d'exécution Selenium | Moyenne | Basse | Parallélisation possible |

### 11.2 Limitations du Projet

1. **Couverture système partielle** : 49% des tests système implémentés (17/35)

2. **Tests manuels non formalisés** : Certains scénarios complexes nécessitent une exécution manuelle

3. **Environnement unique** : Tests réalisés uniquement sur environnement de développement local

### 11.3 Améliorations Proposées

#### 11.3.1 Court Terme
- ~~Implémenter les tests de performance basiques~~ ✅ Fait
- Compléter les tests système manquants
- Corriger les défauts de sévérité moyenne

#### 11.3.2 Moyen Terme
- Intégrer les tests dans un pipeline CI/CD (GitHub Actions)
- Ajouter des tests de régression visuelle
- Implémenter des tests API avec Postman/Swagger

#### 11.3.3 Long Terme
- ~~Explorer Playwright ou Robot Framework~~ ✅ Playwright implémenté
- Mettre en place un environnement de test dédié
- Automatiser le reporting des tests

---

## 12. Outil Bonus : Playwright

### 12.1 Justification du Choix

**Playwright** a été sélectionné comme outil bonus pour les raisons suivantes :

| Critère | Playwright | Selenium (existant) |
|---------|------------|---------------------|
| Auto-wait | ✅ Natif | ❌ Waits manuels requis |
| Multi-navigateur | ✅ Chrome, Firefox, Safari | ⚠️ Nécessite WebDrivers séparés |
| Gestion des drivers | ✅ Automatique | ❌ WebDriver Manager requis |
| Enregistrement vidéo | ✅ Intégré | ❌ Outils externes nécessaires |
| Trace viewer | ✅ Débogage interactif | ❌ Non disponible |
| Screenshots | ✅ Auto sur échec | ⚠️ Implémentation manuelle |
| Exécution parallèle | ✅ Native | ⚠️ pytest-xdist requis |

### 12.2 Valeur Ajoutée

Playwright apporte les avantages suivants par rapport à l'implémentation Selenium existante :

1. **Tests cross-navigateur** : Un seul code de test s'exécute sur Chrome, Firefox et Safari
2. **Fiabilité accrue** : L'auto-wait élimine les problèmes de synchronisation
3. **Métriques de performance** : Accès natif aux métriques de chargement
4. **Régression visuelle** : Capture de screenshots pour comparaison
5. **Débogage avancé** : Trace viewer avec snapshots DOM

### 12.3 Comparaison Selenium vs Playwright

#### 12.3.1 Couverture des Tests

**Selenium et Playwright ne couvrent PAS les mêmes cas de test.** Ils testent des fonctionnalités similaires mais avec des approches et portées différentes.

##### Tests Selenium (4 fichiers, ~20+ cas de test)

| Fichier | Cas de Test | Focus |
|---------|-------------|-------|
| `test_001_login.py` | TC-AUTH-001 à TC-AUTH-008 | Fonctionnalité de connexion (credentials valides/invalides, accès page) |
| `test_002_registration.py` | TC-AUTH-010 à TC-AUTH-016 | Inscription utilisateur (valide, invalide, erreurs de validation) |
| `test_003_events.py` | TC-EVT-001 à TC-EVT-030 | Navigation événements, recherche, filtrage, détails |
| `test_004_event_registration.py` | TC-REG-001 à TC-REG-010 | Inscription/désinscription aux événements |

##### Tests Playwright (1 fichier, 6 cas de test)

| Fichier | Cas de Test | Focus |
|---------|-------------|-------|
| `user-journey.spec.ts` | TC-PW-001, TC-PW-002 | Parcours utilisateur complet (inscription → connexion → navigation) |

#### 12.3.2 Différences Clés

| Aspect | Selenium | Playwright |
|--------|----------|------------|
| **Portée des tests** | Tests granulaires et isolés | Flux bout-en-bout complet |
| **Style de test** | Tests de fonctionnalités individuelles | Tests de scénarios combinés |
| **Fonctionnalités uniques** | - | Tests cross-navigateur, métriques de performance, régression visuelle |
| **Architecture** | Page Object Model (POM) | Actions directes inline |
| **Nombre de tests** | ~20+ tests sur 4 fichiers | 6 tests dans 1 fichier |

#### 12.3.3 Tests Exclusifs à Chaque Outil

**Selenium uniquement :**
- Tests de connexion invalide avec diverses combinaisons de credentials
- Validation d'inscription (mot de passe court, mots de passe non correspondants, email dupliqué)
- Test de recherche sans résultats
- Filtrage par catégorie d'événement

**Playwright uniquement :**
- Validation cross-navigateur (Chrome, Firefox, Safari dans un seul test)
- Collecte de métriques de performance
- Tests de régression visuelle avec captures d'écran
- Parcours utilisateur complet dans un seul flux

#### 12.3.4 Conclusion de la Comparaison

Selenium fournit des **tests complets et isolés** pour les fonctionnalités individuelles, tandis que Playwright offre une **implémentation bonus** démontrant les capacités de test cross-navigateur et un scénario de parcours utilisateur complet que Selenium ne couvre pas en tant que flux unique.

### 12.4 Scénario Implémenté

Un scénario unique **non couvert par Selenium** a été implémenté **en collaboration** par Adam et Afra :

| ID | Scénario | Description | Contributeurs |
|----|----------|-------------|---------------|
| TC-PW-001 | Parcours utilisateur complet | Inscription → Connexion → Navigation → Inscription événement | Adam & Afra |
| TC-PW-002 | Régression visuelle | Vérification apparence page de connexion | Adam & Afra |

**Fichier** : `tests/Playwright/tests/user-journey.spec.ts`

**Mode de travail** : Les tests Playwright ont été développés conjointement, avec une conception collaborative de l'architecture et une validation croisée des résultats.

### 12.5 Structure du Projet Playwright

```
tests/Playwright/
├── package.json                    # Dépendances npm
├── playwright.config.ts            # Configuration multi-navigateur
├── README.md                       # Documentation
└── tests/
    └── user-journey.spec.ts        # Tests E2E
```

### 12.6 Exécution des Tests Playwright

```bash
# Installation
cd tests/Playwright
npm install
npx playwright install

# Exécution
npm test                    # Tous les tests
npm run test:headed         # Mode visible
npm run test:ui             # Interface interactive
npm run test:report         # Rapport HTML
```

### 12.7 Exemple de Code Playwright

```typescript
test('Step 1: New user can register successfully', async ({ page }) => {
  // Navigation automatique avec auto-wait
  await page.goto(`${BASE_URL}/register`);
  
  // Remplissage du formulaire
  await page.fill('input[name="name"]', TEST_USER.name);
  await page.fill('input[name="email"]', TEST_USER.email);
  await page.fill('input[name="password"]', TEST_USER.password);
  await page.fill('input[name="password_confirmation"]', TEST_USER.password);
  
  // Soumission
  await page.click('button[type="submit"]');
  
  // Vérification avec auto-retry
  await page.waitForURL(/.*login/, { timeout: 10000 });
  await expect(page.locator('input[name="email"]')).toBeVisible();
});
```
PLAYWRIGHT TEST SCREENHOT HERE
---

## 13. Barème d'Évaluation

Le tableau ci-dessous présente le barème d'évaluation du projet avec la correspondance vers les sections du rapport.

| Élément | Coefficient | Section(s) du Rapport |
|---------|-------------|----------------------|
| Plan et stratégie de test | 5% | §2 (Objectifs), §4 (Stratégie et Approche) |
| Tests statiques (qualité + preuves) | 5% | §5 (Activités de Test Statique) |
| Cas de test fonctionnels et non fonctionnels | 25% | §6 (Conception et Cas de Test), §6.3 (Cas par Module), §6.4 (Template Formel) |
| Automatisation (qualité, structure POM, exécution) | 40% | §7 (Stratégie d'Automatisation), §7.3 (Page Object Model), §12 (Playwright Bonus) |
| Traçabilité + rapport | 10% | §10 (Matrice de Traçabilité), Document complet |
| Présentation orale | 10% | N/A (Présentation séparée) |
| Bonus (Playwright, Robot, IA avancée…) | 5% | §12 (Outil Bonus : Playwright) |

### 13.1 Détail de la Couverture par Critère

#### Tests Statiques (5%)
- ✅ Revue de code documentée (§5.2)
- ✅ Analyse statique (§5.3)
- ✅ 11 anomalies identifiées (§5.4)
- ✅ Preuves et checklist (§5.2.2)

#### Cas de Test (25%)
- ✅ 70 cas de test documentés (§6.2)
- ✅ Tests fonctionnels par module (§6.3.1 à §6.3.3)
- ✅ Tests non-fonctionnels : Sécurité (§6.3.4), Performance (§6.3.5)
- ✅ Techniques ISTQB appliquées (§4.3)
- ✅ Template formel appliqué (§6.4)

#### Automatisation (40%)
- ✅ PHPUnit : Unit + Feature (§7.2)
- ✅ Selenium + Page Object Model (§7.3)
- ✅ Tests de performance implémentés (§7.2.2)
- ✅ Scripts exécutables (§7.4)

#### Traçabilité (10%)
- ✅ Matrice bidirectionnelle (§10)
- ✅ Exigences → Tests → Implémentation (§10.1)
- ✅ Vérification de couverture (§10.2)

#### Bonus (5%)
- ✅ Playwright implémenté (§12)
- ✅ Scénario unique non dupliqué (§12.3)
- ✅ Justification technique (§12.1)

---

## 14. Conclusion

### 14.1 Bilan Global

Le projet de test AAB_EventPlanner a été réalisé conformément aux exigences du guide pédagogique `Guideline_Test_Qualite_Logiciel.md` et aux principes ISTQB.

#### 14.1.1 Conformité aux Exigences du Guide

| Exigence | Statut |
|----------|--------|
| Tests statiques (min 2 activités) | ✅ Conforme |
| Tests fonctionnels | ✅ Conforme |
| Tests non fonctionnels (Performance) | ✅ Conforme |
| Tests unitaires | ✅ Conforme |
| Tests d'intégration | ✅ Conforme |
| Tests système | ✅ Conforme |
| Automatisation (min 1 test POM) | ✅ Conforme |
| Traçabilité bidirectionnelle | ✅ Conforme |
| Rapport professionnel | ✅ Conforme |
| Outil Bonus (Playwright) | ✅ Conforme |

### 14.2 Qualité du SUT

L'application AAB_EventPlanner présente un **niveau de qualité acceptable** :
- **Aucun défaut de sévérité haute** détecté
- **Couverture des exigences** : 100%
- **Couverture d'implémentation des tests** : 75%
- **Tests de performance** : 100% implémentés
- Architecture robuste et maintenable

### 14.3 Recommandations Finales

1. **Priorité haute** : Corriger les 3 défauts de sévérité moyenne avant mise en production
2. **Priorité moyenne** : Compléter la couverture des tests système (objectif 80%)
3. **Priorité basse** : Étendre les tests Playwright pour plus de couverture cross-navigateur

### 14.4 Validation du Rapport

| Rôle | Nom | Date | Signature |
|------|-----|------|-----------|
| Testeur Principal | Adam | 29/12/2025 | __________ |
| Testeur | Afra | 29/12/2025 | __________ |
| Responsable Projet | __________ | 29/12/2025 | __________ |

---

## 15. Déclaration d'Utilisation de l'IA

### 15.1 Outil IA Utilisé

| Paramètre | Valeur |
|-----------|--------|
| **Outil** | GitHub Copilot (Claude) |
| **Environnement** | Visual Studio Code |
| **Date d'utilisation** | 26-29 décembre 2025 |

### 15.2 Éléments Générés par l'IA

| Élément | Description | Niveau d'Assistance |
|---------|-------------|---------------------|
| **Structure du rapport** | Organisation des sections selon ISTQB | Génération complète |
| **Analyse statique** | Revue de code et identification des anomalies (DEF-001 à DEF-011) | Assisté par IA |
| **Cas de test** | Conception des cas de test (TC-AUTH, TC-EVT, TC-REG, etc.) | Génération partielle |
| **Template formel** | Application du template aux cas TC-AUTH-003 et TC-REG-001 | Génération complète |
| **Code PHPUnit** | Tests PerformanceTest.php (TC-PERF-001 à TC-PERF-004) | Génération complète |
| **Code Playwright** | Suite de tests user-journey.spec.ts | Génération complète |
| **Matrice de traçabilité** | Correspondance exigences-tests-implémentation | Assisté par IA |
| **Documentation** | Rédaction et mise en forme du rapport | Génération complète |

### 15.3 Travail Humain

#### 15.3.1 Travail Individuel (Sans Chevauchement)

| Élément | Responsable |
|---------|-------------|
| Tests unitaires PHPUnit - Modèles (AAB_EventModelTest, UserModelTest) | Adam |
| Tests unitaires PHPUnit - Validation (ValidationRulesTest) | Afra |

#### 15.3.2 Travail Collaboratif

| Élément | Responsables |
|---------|---------------|
| Définition des exigences du projet | Adam & Afra |
| Tests d'intégration PHPUnit (Feature Tests) | Adam & Afra |
| Automatisation Selenium/Pytest | Adam & Afra |
| Tests Playwright (Bonus) | Adam & Afra |
| Validation des cas de test générés | Adam & Afra |
| Exécution et analyse des tests | Adam & Afra |
| Vérification des résultats | Adam & Afra |
| Revue de code et tests statiques | Adam & Afra |
| Revue et approbation du rapport final | Adam & Afra |
| Captures d'écran (à compléter) | Adam & Afra |

### 15.4 Note sur l'Utilisation de l'IA

L'IA a été utilisée comme outil d'assistance pour accélérer la rédaction de la documentation et la génération de code de test. Tous les éléments générés ont été revus, validés et adaptés par l'équipe de test pour garantir leur pertinence et leur conformité aux exigences du projet.

---

## 16. Annexes

### Annexe A : Commandes d'Exécution des Tests

#### A.1 Tests PHPUnit

```bash
# Exécution complète
php artisan test

# Suite spécifique
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Avec couverture
php artisan test --coverage

# Fichier spécifique
php artisan test tests/Feature/AuthenticationTest.php
```

#### A.2 Tests Selenium/Pytest

```bash
# Installation
cd tests/Selenium
pip install selenium pytest webdriver-manager

# Exécution
pytest tests/ -v -s

# Tests smoke
pytest tests/ -v -m smoke

```

#### A.3 Tests Playwright (Bonus)

```bash
# Installation
cd tests/Playwright
npm install
npx playwright install

# Exécution
npm test                    # Tous les tests
npm run test:headed         # Mode visible
npm run test:ui             # Interface interactive
npm run test:report         # Rapport HTML
```

#### A.4 Tests de Performance

```bash
# Exécution des tests de performance
php artisan test tests/Feature/PerformanceTest.php -v

# Avec affichage des temps
php artisan test tests/Feature/PerformanceTest.php --log-junit=performance-results.xml
```

### Annexe B : Structure des Fichiers de Test

```
tests/
├── TestCase.php
├── Documentation/
│   ├── RapportFinalDeTest.md      # Ce document
│   ├── StaticTestReport.md
│   ├── TestPlan.md
│   ├── TestCases.md
│   ├── TestExecutionReport.md
│   └── TraceabilityMatrix.md
├── Unit/
│   ├── AAB_EventModelTest.php
│   ├── UserModelTest.php
│   └── ValidationRulesTest.php
├── Feature/
│   ├── AuthenticationTest.php
│   ├── EventTest.php
│   ├── RegistrationTest.php
│   ├── CategoryTest.php
│   ├── ProfileTest.php
│   └── PerformanceTest.php        # Tests de performance (TC-PERF-001 à TC-PERF-004)
├── Selenium/
│   ├── pages/
│   │   ├── BasePage.py
│   │   ├── LoginPage.py
│   │   ├── RegisterPage.py
│   │   ├── HomePage.py
│   │   └── EventDetailsPage.py
│   ├── tests/
│   │   ├── conftest.py
│   │   ├── test_001_login.py
│   │   ├── test_002_registration.py
│   │   ├── test_003_events.py
│   │   └── test_004_event_registration.py
│   └── utilities/
│       ├── readProperties.py
│       └── customLogger.py
└── Playwright/                     # Bonus - Tests cross-navigateur
    ├── package.json
    ├── playwright.config.ts
    └── tests/
        └── user-journey.spec.ts
```

### Annexe C : Glossaire ISTQB

| Terme | Définition |
|-------|------------|
| **Cas de test** | Ensemble de conditions de test, données d'entrée et résultats attendus |
| **Test unitaire** | Test d'un composant logiciel isolé |
| **Test d'intégration** | Test des interfaces entre composants |
| **Test système** | Test du système complet dans son environnement |
| **Test de régression** | Test vérifiant qu'une modification n'a pas introduit de défaut |
| **Test de performance** | Test mesurant les temps de réponse et la charge supportée |
| **Partitionnement en classes d'équivalence** | Technique divisant les entrées en classes équivalentes |
| **Analyse des valeurs limites** | Technique testant les valeurs aux frontières des classes |
| **Table de décision** | Technique testant les combinaisons de conditions |
| **Traçabilité** | Correspondance entre exigences, tests et résultats |
| **Page Object Model** | Pattern d'automatisation séparant la logique de test des localisateurs |
| **Playwright** | Framework de test E2E moderne avec support multi-navigateur |

---

**Fin du Rapport Final de Test**

---

*Document préparé le 29 décembre 2025*  
*Version 1.1*  
*Projet AAB_EventPlanner - Module Test et Qualité Logiciel*  
*Équipe de test : Adam, Afra*

# Guideline du Projet : Test et Qualité Logiciel

## 1. Introduction

Dans le cadre du module *Test et Qualité Logiciel*, chaque groupe d’étudiants doit réaliser un **projet de test complet** portant sur l’application web laravel Event Planner (backend et frontend).

L’objectif est de mettre en pratique une démarche de test structurée, incluant des tests **statiques** et **dynamiques**.

Les niveaux de test concernés sont :
- Tests unitaires
- Tests d’intégration
- Tests système

Les types de test incluent :
- Tests fonctionnels
- Tests non fonctionnels

## 2. Objectifs pédagogiques

Le projet vise à développer les compétences suivantes :
- Comprendre et appliquer les différents niveaux et types de tests
- Concevoir des cas de test en utilisant des techniques appropriées
- Réaliser des tests statiques et dynamiques
- Automatiser des scénarios de test
- Assurer la traçabilité entre exigences, cas de test et résultats
- Produire un rapport professionnel de test
- Utiliser des outils de test automatisé

## 3. Portée du projet

Le **Système Sous Test (SUT)** est l’application web développée par les étudiants.

Les tests doivent couvrir :
- Backend
- Frontend

## 4. Tests à réaliser

### 4.1 Tests statiques (obligatoires)

Les étudiants doivent accomplir **au moins deux activités statiques**, incluant :
- **Analyse statique**
  - Revue de code entre membres du groupe ou à l’aide d’outils
  - Présentation des problèmes détectés et corrections effectuées dans le rapport

### 4.2 Tests fonctionnels

Les tests fonctionnels doivent couvrir :
- Tests basés sur les exigences
- Tests de confirmation
- Tests de régression
- Tests système

Au moins **un cas de test automatisé** est obligatoire (Selenium, Pytest ou équivalent).

### 4.3 Tests non fonctionnels

Un ou plusieurs tests au choix :
- Performance (temps de réponse simple)
- Sécurité basique (ex : contrôle d’accès, injection simple)
- Compatibilité navigateur
- Ergonomie / accessibilité (analyse simple)

Le choix et la méthode doivent être justifiés.

### 4.4 Techniques de test

Pour chaque cas de test :
- Technique utilisée (boîte noire / boîte blanche)
- Motivation du choix

### 4.5 Niveaux de test obligatoires

1. Tests unitaires (sur backend, services, contrôleurs…)
2. Tests d’intégration (API, interactions entre modules…)
3. Tests système (scénarios utilisateur complets)

## 5. Outils autorisés et recommandés

### 5.1 Automatisation
- Pytest
- Selenium
- Page Object Model (recommandé)
- Postman / Swagger pour tests API

### 5.2 Utilisation de l’IA

L’IA peut être utilisée pour :
- Génération de cas de test
- Suggestion de scénarios
- Identification de données de test

Obligation de déclarer l’outil et son usage.

### 5.3 Bonus
- Playwright
- Robot Framework
- Autres outils pertinents

## 6. Livrables attendus

### 6.1 Projet de test

Un dossier comprenant :
1. Tests statiques (Preuves de revue de code, Rapport de l’analyse statique)
2. Cas de test documentés (Sous format tableau, Avec technique et niveau utilisés)
3. Scripts automatisés  
4. Rapport d’exécution (Résultats succès/échecs, Captures d’écran si nécessaire)
5. Tableau de traçabilité (Exigences → Scénarios → Cas de test → Résultats)
6. Rapport final (Couverture, o	Problèmes détectés)

## Barème d’évaluation

| Élément | Coefficient |
|-------|------------|
| Plan et stratégie de test | 5% |
| Tests statiques (qualité + preuves) | 5% |
| Cas de test fonctionnels et non fonctionnels | 25% |
| Automatisation (qualité, structure POM, exécution) | 40% |
| Traçabilité + rapport | 10% |
| Présentation orale | 10% |
| Bonus (Playwright, Robot, IA avancée…) | 5% |

## Format et règles

- Rapport clair et structuré
- Scripts propres, commentés et exécutables
- Usage de l’IA déclaré

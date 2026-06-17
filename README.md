# API Artisan Électricien

Ce projet expose une API RESTful permettant d’alimenter un site vitrine pour un artisan électricien ainsi qu’un espace d’administration sécurisé.

---

## 🔗 Sommaire

- [1. Présentation de l'API](#1-présentation-de-lapi)
- [2. Authentification](#2-authentification)
- [3. Endpoints Publics](#3-endpoints-publics)
- [4. Endpoints Admin](#4-endpoints-admin)
- [5. Réponses JSON - Frontend](#5-réponses-json---frontend)
- [6. Réponses JSON - Admin](#6-réponses-json---admin)
- [7. Gestion des erreurs](#7-gestion-erreurs)
---

## 1. Présentation de l'API

Cette API permet de gérer les entités suivantes :

- Prestations proposées par l’artisan
- Chantiers réalisés (références)
- Certifications professionnelles
- Partenaires du réseau
- Messages envoyés depuis le formulaire de contact (si utilisé)
- Gestion des données depuis un back-office admin

---

## 2. Authentification

Les routes sous `/api/admin/...` sont protégées et nécessitent un utilisateur authentifié avec le rôle `ROLE_ADMIN`.

> Les routes publiques sous `/api/...` sont librement accessibles.

---

## 3. Endpoints Publics

| Méthode | Endpoint                      | Description                             |
|---------|-------------------------------|-----------------------------------------|
| GET     | `/api/prestations`           | Liste des prestations                    |
| GET     | `/api/prestations/:id`       | Détail d'une prestation                  |
| GET     | `/api/works/latest?limit=3&sort=end_date`| Derniers chantiers                       |
| GET  | `/api/works`  | Tous les chantiers |
| GET     | `/api/works/list?page=1&limit=3` | Affiche les chantiers terminés page par page |
| GET     | `/api/works/:id`             | Détail d’un chantier                     |
| GET     | `/api/certifications`        | Liste des certifications valides        |
| GET     | `/api/partners`              | Logos + liens des partenaires           |
| POST    | `/api/contact`               | (Optionnel) Formulaire de contact       |

---

## 4. Endpoints Admin

| Méthode | Endpoint                        | Description                            |
|---------|---------------------------------|----------------------------------------|
| GET     | `/api/admin/prestations`        | Liste complète                         |
| GET     | `/api/admin/prestations/:id`    | Détail d’une prestation                |
| POST    | `/api/admin/prestations`        | Ajouter une prestation                 |
| PUT     | `/api/admin/prestations/:id`    | Modifier une prestation                |
| DELETE  | `/api/admin/prestations/:id`    | Supprimer une prestation               |
| ...     | *(Idem pour works, certifications, partners, contacts)* | |

---

## 5. Réponses JSON - Frontend

### 📦 Prestations – `GET /api/prestations`

```json
[
  {
    "id": 1,
    "name": "Mise en conformité électrique",
    "tarif": 120,
    "image": {
      "url": "/uploads/prestations/conformite.jpg",
      "alt": "Schéma installation électrique"
    }
  },
    {
    "id": 2,
    "name": "Installation de tableau électrique",
    "tarif": 300,
    "image": {
      "url": "/uploads/prestations/tableau.jpg",
      "alt": "Tableau électrique moderne"
    }
  }
]
```
### 📦 Détail prestation – GET /api/prestations/:id

```json
{
  "name": "Mise en conformité",
  "tarif": 120,
  "description": "Contrôle complet selon la norme NFC 15-100...",
  "image": {
    "url": "/uploads/prestations/conformite.jpg",
    "alt": "Schéma installation électrique"
  }
}
```

### 📦 Certifications – GET /api/certifications?valid=true

```json
[
  {
    "id": 1,
    "name": "IRVE",
    "image": {
      "url": "/uploads/certifications/irve.jpg",
      "alt": "Logo IRVE borne de recharge"
    }
  },
  {
    "id": 2,
    "name": "QualiPV",
    "image": {
      "url": "/uploads/certifications/qualipv.jpg",
      "alt": "Logo QualiPV photovoltaïque"
    }
  }
]
```

### 📦 Chantiers – GET /api/works/latest?limit=3&sort=end_date

```json
[
  {
    "id": 15,
    "client": { "display_name": "Société ÉlecPlus" },
    "end_date": "2024-05-10T00:00:00+00:00",
    "image": {
      "url": "/uploads/chantier15/photo.jpg",
      "alt": "Remplacement armoire électrique"
    }
  },
  {
    "id": 12,
    "client": { "display_name": "Mme Alice Martin" },
    "end_date": "2024-06-01T00:00:00+00:00",
    "image": {
      "url": "/uploads/chantier12/photo.jpg",
      "alt": "Rénovation cuisine terminée"
    }
  }
]
```

### 📦 Chantiers (avec la pagination) – GET /api/works/list?page=1&limit=3

```json
{
  "page": 1,
  "limit": 3,
  "total": 10,
  "items": [
    {
      "id": 19,
      "client": {
        "displayName": "Caroline Loiseau"
      },
      "endDate": "2025-08-28T00:00:00+00:00",
      "image": {
        "url": "https://picsum.photos/seed/68920768af5d2/300/200",
        "alt": "Bovary, à trouver."
      }
    },
    {
      "id": 17,
      "client": {
        "displayName": "Isaac Pelletier"
      },
      "endDate": "2025-08-12T00:00:00+00:00",
      "image": {
        "url": "https://picsum.photos/seed/68920768aa847/300/200",
        "alt": "Ah! la voici! fit."
      }
    },
    {
      "id": 13,
      "client": {
        "displayName": "Gabrielle Charles"
      },
      "endDate": "2025-08-10T00:00:00+00:00",
      "image": {
        "url": "https://picsum.photos/seed/689207689fef2/300/200",
        "alt": "Domingo, le chien."
      }
    }
  ]
}
```

### 📦 Détail chantier (client particulier) - GET /api/works/:id

```json
{
  "id": 15,
  "client": { "display_name": "Mme Alice Martin" },
  "description": "Décrire le chantier...",
  "start_date": "2024-06-01T00:00:00+00:00",
  "end_date": null,
  "duration_in_days": null,
  "images": [
    {
      "url": "/uploads/chantiers/19/avant.jpg",
      "alt": "Avant intervention"
    }
  ]
}
```

### 📦 Détail chantier (Entreprise) - GET /api/works/:id

```json
{
  "id": 18,
  "client": { "display_name": "Société ÉlecPlus" },
  "description": "Rénovation complète des installations électriques d’un local commercial.",
  "start_date": "2024-05-01T00:00:00+00:00",
  "end_date": "2024-05-15T00:00:00+00:00",
  "duration_in_days": 14,
  "images": [
    {
      "url": "/uploads/chantiers/18/photo1.jpg",
      "alt": "Tableau avant intervention"
    },
    {
      "url": "/uploads/chantiers/18/photo2.jpg",
      "alt": "Nouvelle armoire électrique posée"
    }
  ]
}
```
### 📦 Partenaires - GET /api/partners

```json
[
  {
    "id": 3,
    "name": "Hager",
    "site_web": "https://www.hager.fr",
    "image": {
      "url": "/uploads/partners/hager.jpg",
      "alt": "Logo Hager"
    }
  },
{
    "id": 1,
    "name": "Legrand",
    "site_web": "https://www.legrand.fr",
    "image": {
      "url": "/uploads/partners/legrand.jpg",
      "alt": "Logo Legrand"
    }
  }
]
```

## 6. Réponses JSON - Admin

### 🛠 Prestations – GET /api/admin/prestations

```json
[
  {
    "id": 1,
    "name": "Mise en conformité",
    "tarif": 120,
    "description": "Contrôle complet selon la norme NFC 15-100...",
    "image": {
      "url": "/uploads/prestations/conformite.jpg",
      "alt": "Schéma installation électrique"
    },
    "created_at": "2024-05-01T10:00:00"
  }
]
```

### 🛠 Partenaires – GET /api/admin/partners

```json
[
  {
    "id": 1,
    "name": "Partenaire Alpha",
    "site_web": "https://www.partenaire-alpha.com",
    "image": {
      "url": "/uploads/partners/alpha.jpg",
      "alt": "Logo Partenaire Alpha"
    }
  },
  {
    "id": 3,
    "name": "Partenaire Beta",
    "site_web": "https://www.partenaire-beta.com",
    "image": {
      "url": "/uploads/partners/beta.jpg",
      "alt": "Logo Partenaire Beta"
    }
  }
]
```

### 🛠 Contacts - GET /api/admin/contacts

```json
[
  {
    "id": 1,
    "lastname": "Bernard",
    "email": "contact1@example.com",
    "subject": "Demande de devis",
    "excerpt": "Bonjour, je souhaite installer un tableau...",
    "created_at": "2024-06-21T15:42:00"
  },
  {
    "id": 2,
    "lastname": "Lambert",
    "email": "contact2@example.com",
    "subject": "Problème prise",
    "excerpt": "Une prise ne fonctionne plus dans la cuisine...",
    "created_at": "2024-06-22T09:05:00"
  }
]
```

### 🛠 Détail Contact - GET /api/admin/contacts/:id

```json
{
  "id": 1,
  "lastname": "Bernard",
  "email": "contact1@example.com",
  "subject": "Demande de devis",
  "message": "Bonjour, je souhaite installer un tableau électrique dans mon garage. Merci de me recontacter.",
  "has_accepted_policies": true,
  "created_at": "2024-06-21T15:42:00"
}
```

## 7. Gestion des erreurs 

### 400 - Bad request

```json
{
  "status": 400,
  "error": "Bad request",
  "message": "Requête mal formée."
}
```

### 401 - Unauthorized

```json
{
  "status": 401,
  "error": "Unauthorized",
  "message": "Veuillez vous authentifier."
}
```

### 403 - Forbidden

```json
{
  "status": 403,
  "error": "Forbidden",
  "message": "Accès refusé (rôle insuffisant)."
}
```

### 404 - Not found

```json
{
  "status": 404,
  "error": "Not found",
  "message": "Cette prestation n'existe pas."
}
```

### 422 - Validation failed

```json
{
  "status": 422,
  "error": "Validation Failed",
  "violations": [
    {
      "field": "email",
      "message": "Ce champ est requis."
    }
  ]
}
```
Le champ violation est uniquement utilisé pour les erreurs de validation (422). Chaque objet indique le champ et le message associé. 

### 500 - Internal server error

```json
{
  "status": 500,
  "error": "Internal Server Error",
  "message": "Une erreur inattendue est survenue. Veuillez réessayer plus tard."
}
```




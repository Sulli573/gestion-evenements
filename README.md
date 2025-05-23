# gestion-evenements

**Cloner le dépot:**
**git clone https://github.com/Sulli573/gestion-evenements.git**
**Récupérer la base de données dans le dépot (gestion_evenements.sql)

**Aller sur http://localhost/PHP2/views/template/shows-events.php** 
## Compte admin 
**sulli@123.com**
**mot de passe: 123**

### Compte utilisateur
**sulli@1234.com**
**mot de passe:1234**

**Accès espace admin réservé aux admin :**
**http://localhost/PHP2/views/template/admin/utilisateurs.php**
**Pas possible de réserver un ticket sauf si enregistré en tant qu'utilisateur**

**Espace admin: Ajout Modification Suppression d'événements, de lieux, d'organisateurs. Modification et suppression d'utilisateurs**

# Base de données pour la gestion des événements

Ce document décrit les tables et leurs colonnes pour une base de données de gestion d'événements.

---

## Table `EVENEMENTS`

| Nom colonne          | Type de données | Taille | Contraintes                      | Description                                      |
|----------------------|-----------------|--------|---------------------------------|--------------------------------------------------|
| `id_evenement`       | `int`           |        | Clé primaire, auto increment     | Identifiant de l'événement                      |
| `nom_evenement`      | `Var char`      | 50     |                                 | Nom de l'événement                              |
| `date_evenement`     | `datetime`      |        |                                 | Date de l'événement                             |
| `heure_debut`        | `time`          |        |                                 | Heure de début de l'événement                   |
| `heure_fin`          | `time`          |        |                                 | Heure de fin de l'événement                     |
| `description_evenement` | `text`       |        |                                 | Description de l'événement                        |
| `place_evenement`    | `int`           |        |                                 | Place totale de l'événement                     |
| `place_restantes`    | `int`           |        |                                 | Place restante de l'événement                    |
| `prix_evenement`     | `double`        |        |                                 | Prix de l'événement                             |
| `image_evenement`    | `Var char`      | 50     |                                 | Image de l'événement                            |
| `type_evenement`     | `Var char`      | 255    |                                 | Type de l'événement (concert, pièce de théâtre...) |
| `is_finish`          | `bit`           |        |                                 | Indique si l'événement est terminé              |
| `id_organisateur`   | `int`           |        | Clé étrangère vers `ORGANISATEUR` | Référence à l'organisateur                      |
| `id_lieu`            | `int`           |        | Clé étrangère vers `LIEU`       | Référence au lieu de l'événement                |

---

## Table `INSCRIRE`

| Nom colonne          | Type de données | Taille | Contraintes                      | Description                                      |
|----------------------|-----------------|--------|---------------------------------|--------------------------------------------------|
| `code`               | `int`           |        | Clé primaire, auto incrément    | Code de référence de l'inscription              |
| `Id_utilisateur`     | `int`           |        | Clé étrangère vers `UTILISATEUR`| Référence à l'utilisateur                       |
| `Id_evenement`       | `int`           |        | Clé étrangère vers `EVENEMENTS` | Référence à l'événement                         |
| `Nbr_ticket`         | `int`           |        |                                 | Nombre de tickets réservés                      |
| `Date_inscription`   | `date`          |        |                                 | Date de l'inscription                           |

---

## Table `LIEU`

| Nom colonne          | Type de données | Taille | Contraintes                      | Description                                      |
|----------------------|-----------------|--------|---------------------------------|--------------------------------------------------|
| `id`                 | `int`           |        | Clé primaire                    | Identifiant du lieu                             |
| `Nom_lieu`           | `Var char`      |        |                                 | Nom du lieu                                     |
| `Adresse_lieu`       | `Var char`      |        |                                 | Adresse du lieu                                 |

---

## Table `ORGANISATEUR`

| Nom colonne          | Type de données | Taille | Contraintes                      | Description                                      |
|----------------------|-----------------|--------|---------------------------------|--------------------------------------------------|
| `id`                 | `int`           |        | Clé primaire, auto incrément    | Identifiant de l'organisateur                   |
| `Nom_organisateur`  | `Var char`      | 50     |                                 | Nom de l'organisateur                           |
| `Contact_organisateur` | `Var char` | 50     |                                 | Numéro de téléphone de l'organisateur           |
| `Email_organisateur` | `Var char`      | 255    |                                 | Email de l'organisateur                          |

---

## Table `UTILISATEUR`

| Nom colonne          | Type de données | Taille | Contraintes                      | Description                                      |
|----------------------|-----------------|--------|---------------------------------|--------------------------------------------------|
| `Id_utilisateur`     | `int`           |        | Clé primaire, auto incrément    | Identifiant de l'utilisateur                    |
| `Nom_utilisateur`    | `Var char`      | 50     |                                 | Nom de l'utilisateur                           |
| `Courriel_utilisateur` | `Var char` | 50     |                                 | Email de l'utilisateur                          |
| `Mot_de_passe_utilisateur` | `Var char` | 255    | Haché                           | Mot de passe de l'utilisateur                   |
| `Rôle_utilisateur`   | `Var char`      |        |                                 | Rôle de l'utilisateur (utilisateur, admin, etc.) |
| `Is_active`          | `tinyint`       | 1      |                                 | Utilisateur activé                              |
| `Is_suspended`       | `tinyint`       | 1      |                                 | Utilisateur suspendu (langage grossier, etc.)    |
| `Motif_suspension`   | `Var char`      | 255    |                                 | Motif de la suspension                          |


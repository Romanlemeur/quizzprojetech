# QuizzProjeTech 🎮

Une plateforme de quiz interactive avec design rétro/gaming construite avec CodeIgniter 4.

## 🌟 Fonctionnalités

### Interface Administrateur

- **Gestion des quiz** : Création, modification, suppression
- **Gestion des questions** : 4 options par question avec une seule réponse correcte
- **Upload d'images** : Illustrations pour les quiz
- **Quiz en direct** : Lancement et gestion de quiz temps réel
- **Contrôle live** : Interface de gestion en temps réel avec navigation manuelle des questions
- **Modification des scores** : Ajustement des scores des participants en direct
- **Statistiques** : Suivi des performances et résultats

### Interface Joueur

- **Système d'authentification** : Inscription/connexion
- **Navigation par catégories** : Organisation claire des quiz
- **Quiz en direct** : Participation aux sessions live
- **Timer automatique** : 10 secondes par question (configurable)
- **Classement temps réel** : Leaderboard dynamique
- **Historique** : Suivi des scores personnels

## 🎨 Design

Interface rétro/gaming avec :

- **Couleurs néon** : Vert `#39FF14` et Rose `#FF1177`
- **Polices** : Orbitron (titres) et VT323 (texte)
- **Effets** : Glow, text-shadow, animations CSS
- **Thème** : Cyberpunk/gaming des années 80

## 🛠️ Technologies

- **Backend** : CodeIgniter 4
- **Frontend** : HTML5, CSS3, JavaScript
- **Base de données** : MySQL
- **Serveur** : Apache (XAMPP)
- **Dépendances** : Composer

## 📦 Installation

### Prérequis

- XAMPP avec PHP 8.0+ et MySQL
- Composer

### Étapes

1. **Cloner le projet**

```bash
git clone [url-du-repo]
cd quizzprojetech
```

2. **Installer les dépendances**

```bash
composer install
```

3. **Configuration de la base de données**

   - Créer une base de données `quiz_db`
   - Importer `database.sql`
   - Configurer `quizmaster/app/Config/Database.php`

4. **Configuration environnement**

   - Copier `env-example` vers `.env`
   - Configurer les paramètres selon votre environnement

5. **Permissions**
   - Vérifier les permissions sur `writable/`
   - Créer le dossier `quizmaster/public/uploads/quiz/`

## 🚀 Utilisation

### Démarrage

1. Démarrer XAMPP (Apache + MySQL)
2. Aller sur `http://localhost/quizzprojetech`

### Accès Admin

- URL : `http://localhost/quizzprojetech/quizmaster/public/admin`
- Créer un compte admin via la base de données (role='admin')

### Fonctionnalités Principales

#### Création de Quiz

1. Interface admin → "Créer un Quiz"
2. Remplir titre, description, catégorie
3. Ajouter questions (min. 3) avec 4 options chacune
4. Upload d'image optionnel
5. Activer/désactiver

#### Quiz en Direct

1. Sélectionner un quiz → "Lancer en Live"
2. Les joueurs peuvent rejoindre via `/quiz/live`
3. Navigation manuelle des questions par l'admin
4. Modification des scores en temps réel
5. Visualisation du classement live

## 📁 Structure

```
quizzprojetech/
├── quizmaster/           # Application CodeIgniter 4
│   ├── app/
│   │   ├── Controllers/  # Contrôleurs (Admin, Quiz, Auth)
│   │   ├── Models/       # Modèles de données
│   │   ├── Views/        # Vues (admin, quiz)
│   │   └── Config/       # Configuration
│   └── public/           # Point d'entrée web
│       ├── css/          # Styles CSS
│       ├── js/           # Scripts JavaScript
│       └── uploads/      # Fichiers uploadés
├── includes/             # Fichiers partagés (header, footer)
├── assets/              # Ressources statiques
├── database.sql         # Structure de base de données
└── *.php               # Pages statiques (index, login, register)
```

## 🔧 Configuration

### Base de Données

Éditer `quizmaster/app/Config/Database.php` :

```php
public array $default = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'quiz_db',
    'DBDriver' => 'MySQLi',
];
```

### Environnement

Fichier `.env` :

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/quizzprojetech/quizmaster/public/'
```

## 🎯 Roadmap

- [ ] Système de badges et récompenses
- [ ] Export des résultats en PDF
- [ ] Quiz multijoueurs avec équipes
- [ ] Intégration graphiques Chart.js
- [ ] Mode hors-ligne
- [ ] API REST pour mobile

## 🤝 Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/ma-feature`)
3. Commiter (`git commit -am 'Ajout de ma feature'`)
4. Push (`git push origin feature/ma-feature`)
5. Créer une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir `LICENSE` pour plus de détails.

## 🎮 Crédits

Projet développé avec amour pour une expérience de quiz rétro-gaming unique !

---

**Fait avec ❤️ et beaucoup de café ☕**

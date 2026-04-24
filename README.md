# 🐾 Les Gardiens du Trident

Application web développée avec **Symfony** dans le cadre de la formation **DWWM**.  
Ce projet permet de présenter un élevage de chiens Leonberg et de gérer la réservation de chiots ainsi qu’une boutique d’articles.

---

##  Fonctionnalités

-  Inscription / Connexion utilisateur  
   Consultation et réservation de chiots  
-  Panier et gestion des commandes  
-  Back-office administrateur (CRUD)  
-  Formulaire de contact avec envoi d’email  

---

##  Stack technique

- **Back-end** : PHP / Symfony  
- **Front-end** : HTML, CSS, JavaScript, Bootstrap  
- **Template** : Twig  
- **Base de données** : MySQL  
- **ORM** : Doctrine  

---

##  Sécurité

- Authentification et rôles (USER / ADMIN)  
- Protection CSRF  
- Hash des mots de passe  

---

## Améliorations prévues
- Paiement en ligne
- Dashboard admin avancé
- Emails automatiques
- Optimisation SEO

##  Installation

```bash
git clone https://github.com/ton-repo/gardiens-du-trident.git
cd gardiens-du-trident
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony serve




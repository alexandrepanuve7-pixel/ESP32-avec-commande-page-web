# 📡 Projet ESP32 - Envoi de données + Contrôle Web 

---

##  Description du projet

Ce projet met en œuvre un système complet de communication entre un **ESP32** et un **serveur web**.

Depuis mon ordinateur physique, je me suis servie de UBUNTU, une machine virtuelle que j’ai utilisé via le logiciel de virtualisation « Oracle Virtual Box » pour pouvoir héberger des fichier «. PHP » qui me serviront à faire m page web. Cette machine UBUNTU agira comme un PC à part entière, je devais crée une page web qui as pour but d’envoyer une requête a un microcontrôleur (ESP32) qui actionne une LED est un BUZZER.
Pour cela j’ai donc crée deux fichiers ".PHP", que j'ai au préalable codé sur ma machine physique et que j’ai mis dans ma machine UBUNTU, dans le même dossier qui est nommé « BTSCIEL », le chemin d'accès est "var/www/html/btsciel", il y a un fichier nommé "data. PHP" et l'autre "index. PHP". 
Le fichier nommée "data. PHP" est ma page web dynamique, que j'ai paramétrer pour pouvoir envoyer des requêtes à mon ESP32, qui elle se chargeras d’allumer la LED ou le BUZZER, en fonction de mon choix depuis le site web. (Voir code)
Ensuite mon « index. PHP », me sert a bloqué l’accès à la racine de mes fichiers. par exemple,


---

###  Les objectifs de ce projet :

* Envoyer automatiquement des données depuis l’ESP32 vers un serveur
* Stocker ces données côté serveur
* Afficher la valeur reçue depuis l'ESP32 sur une page web
* Permettre à l’utilisateur de contrôler des composants (LED, buzzer) à distance

Ce projet illustre les bases de l’**IoT (Internet of Things)** :

* communication réseau                     → échange de données via le WiFi 
* interaction client / serveur             → envoi de données (ESP32) et réception (PHP)  
* interface utilisateur simple             → affichage et contrôle via une page web  

---

##  Technologies utilisées

###  Côté matériel

- **ESP32** → microcontrôleur avec WiFi intégré  
- **LED** → indicateur lumineux  
- **Buzzer** → signal sonore                                   

###  Côté logiciel

* Arduino IDE (programmation de l'ESP32)   → programmation et téléversement du code sur l’ESP32  
* WiFi (connexion réseau)                  → avoir une connexion wifi
* HTTP (protocole de communication)        → protocole utilisé pour envoyer et recevoir des données (POST / GET)  
* PHP (traitement serveur)                 → mes fichiers "data.php" et "index.php"
* HTML / CSS (interface web)               → intégré dans mon code "data.php"
* JavaScript (interaction avec l’ESP32)    → interaction avec l’ESP32 depuis la page web (boutons, requêtes)

---

##  Fonctionnement global

Le système fonctionne en 3 parties principales :

---

### 1️ ESP32 (client + serveur)

L’ESP32 joue **deux rôles** :

####  Client HTTP

* Il envoie une valeur aléatoire toutes les 2 secondes
* Cette valeur est envoyée au serveur via une requête **POST**

Exemple :

```
valeur=57
```

####  Serveur web embarqué

* L’ESP32 héberge aussi un petit serveur web
* Il écoute les requêtes provenant du navigateur

Routes disponibles :

* `/led` → allume la LED
* `/son` → active le buzzer

 Cela permet de contrôler le matériel à distance via une page web

---

### 2 Serveur PHP

Le serveur agit comme **récepteur et stockage des données**.

####  Réception des données

Le serveur PHP joue le rôle de récepteur entre l’ESP32 et l’application web.

* Il reçoit les données envoyées par l’ESP32 via une requête HTTP **POST**
* Il vérifie que la variable `valeur` est bien présente dans la requête → la variable "valeur" est une variable de communication utilisée dans la requête HTTP
* Si la donnée existe, elle est ensuite traitée et stockée côté serveur
  
####  Stockage

* La valeur est enregistrée dans un fichier nommer :

```
valeur.txt
```

####  Lecture

* Lorsqu’un utilisateur ouvre la page web :

  * le serveur lit le fichier "valeur.txt"
  * affiche la dernière valeur reçue

---

### 3️ Interface Web

La page web permet :

####  Affichage

* Voir la dernière valeur envoyée par l’ESP32
* Rafraîchissement automatique (toutes les 2 à 5 secondes)

####  Contrôle

* Bouton **LED** → envoie une requête à l’ESP32 pour allumer une led
* Bouton **Buzzer** → déclenche un son

Exemple de commande envoyée :

```
http://IP_ESP32/led
```
🔴 Afin de trouver l'ip de l'esp 32 il faut utiliser la commande "Serial.println(WiFi.localIP());" (voir code IP ESP32)
---

##  Structure du projet

```
📦 projet-esp32
 ┣ 📜 arduino.ino       → Programme Arduino de l’ESP32
 ┣ 📜 data.php       → Script serveur + interface web
 ┣ 📜 valeur.txt      → Fichier de stockage des données
 ┗ 📜 README.md       → Documentation du projet
```

###  Détail des fichiers

* **arduino.ino**

  * Gère le WiFi 
  * Envoie les données
  * Contrôle LED et buzzer

* **data.php**

  * Reçoit les données (POST)
  * Lit le fichier (GET)
  * Génère la page web

* **valeur.txt**

  * Stocke la dernière valeur reçue

---

##  Installation et mise en place

---

### 🔧 1. Configuration de l’ESP32

Dans le code Arduino, modifier :

```cpp
const char* ssid = "VOTRE_WIFI";
const char* password = "MOT_DE_PASSE";
const char* serverName = "http://IP_SERVEUR/index.php";
```
🔴 Dans mon cas "IP_SERVEUR" était l'addresse IP de ma machine Ubuntu

 Important :

* `ssid` → nom du WiFi
* `password` → mot de passe
* `serverName` → adresse du serveur PHP

🔴 il faut sélectionner le bon port lorsque vous brancher votre ESP32 "outils/port/*****" et aussi a sélectionner la bonne carte "outils/type de carte/ ESP32 dev module"

Ensuite :

* Compiler le code 
* Téléverser sur l’ESP32

---

###  2. Configuration du serveur PHP

* Placer `data.php` sur un serveur web

  * (ex: XAMPP, WAMP, serveur distant)
* Vérifier que PHP fonctionne en le testant l’accès via le navigateur : http://localhost/data.php
* Créer le fichier : 

```
valeur.txt
```
🔴 Il est important de donner les droits d'écriture au fichier "valeur.txt" en utilisant la fonction "chmod 777 /chemin/vers/le/fichiers/data.php/" dans l'invite de commande. Si non sa ne sauvegardera pas la valeur envoyer depuis l'ESP32

---

###  3. Accès à l’interface

Ouvrir dans un navigateur :

```
http://IP_SERVEUR/data.php   
```
🔴Dans mon cas "IP_SERVEUR" était l'addresse IP de ma machine Ubuntu
---

##  Exemple de communication

###  Requête envoyée par l’ESP32

L’ESP32 envoie une requête HTTP de type **POST** pour transmettre une valeur au serveur PHP.

#### Exemple de requête :

`POST /data.php`  → envoie les données au fichier PHP

`Content-Type` : application/x-www-form-urlencoded → indique le format des données envoyées

`valeur=42`  → donnée transmise par l’ESP32

###  Réponse du serveur

```
Valeur enregistrée : 42
```

---


* Projet réalisé dans le cadre de : (BTS / cours / perso)

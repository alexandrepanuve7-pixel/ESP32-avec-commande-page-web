#include <WiFi.h>        // Gestion du WiFi
#include <WebServer.h>   // Serveur web embarqué
#include <HTTPClient.h>  // Requêtes HTTP (POST)

// ----------- PARAMÈTRES WIFI -----------
const char* ssid = "********";        // Nom du réseau WiFi
const char* password = "********";  // Mot de passe WiFi

// Adresse du serveur qui reçoit les données (⚠️ mettre l'IP du serveur, pas de l'ESP32)
const char* serverName = "http://IP_DU_SERVEUR/chemin/script.php";

// Création du serveur web sur le port 80
WebServer server(80);

// ----------- BROCHES -----------
int led = 2;        // GPIO de la LED (à adapter)
int buzzer = 15;    // GPIO du buzzer (à adapter)

// ----------- PARAMÈTRES BUZZER (PWM) -----------
int freq = 2000;        // Fréquence PWM
int resolution = 8;     // Résolution PWM (8 bits)

// ----------- GESTION DU TEMPS -----------
unsigned long previousMillis = 0;  // Stocke le dernier envoi
const long interval = 2000;        // Intervalle d'envoi (2 secondes)

// ----------- CONNEXION WIFI -----------
void connectWiFi() {
  WiFi.begin(ssid, password);  // Démarre la connexion WiFi

  // Boucle tant que non connecté
  while (WiFi.status() != WL_CONNECTED) {
    delay(200); // Petit délai (OK seulement au démarrage)
  }

  // Affiche confirmation + IP
  Serial.println("\nConnecté au WiFi !");
  Serial.print("Adresse IP : ");
  Serial.println(WiFi.localIP());
}

// ----------- ENVOI DE DONNÉES -----------
void sendData() {

  int valeur = random(0, 100);  // Génère une valeur aléatoire (0 à 99)

  HTTPClient http;              // Création de l'objet HTTP
  http.begin(serverName);       // Initialise la connexion vers le serveur

  // Spécifie le type de données envoyées (format formulaire)
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  // Prépare les données à envoyer
  String postData = "valeur=" + String(valeur);

  // Envoie la requête POST
  int code = http.POST(postData);

  // Affichage debug
  Serial.println("--------------");
  Serial.print("Valeur envoyée : ");
  Serial.println(valeur);

  Serial.print("Code HTTP : ");
  Serial.println(code);  // 200 = OK

  // Si le serveur répond
  if (code > 0) {
    String response = http.getString();  // Récupère la réponse
    Serial.println("Réponse serveur : ");
    Serial.println(response);
  }

  http.end();  // Ferme la connexion
}

// ----------- GESTION LED -----------
void handleLed() {

  digitalWrite(led, HIGH);  // Allume la LED

  delay(300);               // ⚠️ Bloque le programme (à éviter en version avancée)

  digitalWrite(led, LOW);   // Éteint la LED

  // Réponse HTTP envoyée au navigateur
  server.send(200, "text/plain", "LED OK");
}

// ----------- GESTION BUZZER -----------
void handleBuzzer() {

  // Configure le PWM (⚠️ normalement à faire dans setup)
  ledcAttach(buzzer, freq, resolution);

  // Joue une petite "mélodie"
  ledcWriteTone(buzzer, 1000);
  delay(300);

  ledcWriteTone(buzzer, 500);
  delay(300);

  ledcWriteTone(buzzer, 1500);
  delay(300);

  ledcWriteTone(buzzer, 0); // Coupe le son

  // Réponse HTTP
  server.send(200, "text/plain", "BUZZER OK");
}

// ----------- SETUP -----------
void setup() {

  Serial.begin(115200);  // Initialise la communication série

  pinMode(led, OUTPUT);  // Configure la LED en sortie
  digitalWrite(led, LOW);

  connectWiFi();         // Connexion au WiFi

  // Association des URLs aux fonctions
  server.on("/led", handleLed);     // http://IP_ESP32/led
  server.on("/son", handleBuzzer);  // http://IP_ESP32/son

  server.begin();        // Démarre le serveur web

  Serial.println("Serveur ESP32 prêt !");
}

// ----------- LOOP PRINCIPALE -----------
void loop() {

  server.handleClient();  // Gère les requêtes HTTP entrantes

  // Vérifie si le WiFi est toujours connecté
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();  // Reconnexion automatique
    return;
  }

  // Envoi de données toutes les 2 secondes (sans delay)
  if (millis() - previousMillis >= interval) {
    previousMillis = millis();  // Met à jour le temps
    sendData();                // Envoie les données
  }
}
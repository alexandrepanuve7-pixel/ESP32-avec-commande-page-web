#include <WiFi.h>  // Bibliothèque WiFi pour ESP32

// Remplace par le nom de ton réseau WiFi
const char* ssid = "TON_WIFI";

// Remplace par ton mot de passe WiFi
const char* password = "TON_MDP";

void setup() {
  // Démarre la communication série (pour afficher les infos sur le PC)
  Serial.begin(115200);

  // Lancement de la connexion WiFi
  WiFi.begin(ssid, password);

  // Attente de la connexion WiFi
  // Tant que l'ESP32 n'est pas connecté, on affiche un point "."
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);          // Pause de 0,5 seconde
    Serial.print(".");   // Indique que la connexion est en cours
  }

  // Une fois connecté au WiFi
  Serial.println("\nConnecté !");

  // Affiche l'adresse IP attribuée à l'ESP32 par le routeur
  Serial.print("IP ESP32 : ");
  Serial.println(WiFi.localIP());
}

void loop() {
  // Rien ici pour le moment
  // (le programme ne fait qu'établir la connexion WiFi)
}
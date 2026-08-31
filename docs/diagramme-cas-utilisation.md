# Diagramme de Cas d'Utilisation — Plateforme ƉƆKUN

## Acteurs

| Acteur | Description |
|--------|-------------|
| **Visiteur** | Non authentifié, consulte le catalogue |
| **Touriste** | Membre authentifié (rôle tourist) |
| **Artisan** | Membre authentifié (rôle artisan) |
| **Admin** | Administrateur de la plateforme |
| **Système** | Automatismes (cron, webhooks) |
| **FedaPay** | Passerelle de paiement externe |

---

## Diagramme Principal

```mermaid
useCaseDiagram
    left to right direction
    actor "Visiteur" as V
    actor "Touriste" as T
    actor "Artisan" as A
    actor "Admin" as ADM
    actor "Système" as S
    actor "FedaPay" as FP

    package "Découverte" {
        useCase "Consulter l'accueil" as UC1
        useCase "Parcourir les artisans" as UC2
        useCase "Voir profil artisan" as UC3
        useCase "Consulter la carte" as UC4
        useCase "Parcourir les expériences" as UC5
        useCase "Consulter le savoir-faire" as UC6
        useCase "Consulter les moments" as UC7
        useCase "Consulter à propos" as UC8
        useCase "Consulter contact" as UC9
        useCase "Changer de langue" as UC10
    }

    package "Authentification" {
        useCase "S'inscrire" as UC11
        useCase "Se connecter" as UC12
        useCase "Se déconnecter" as UC13
    }

    package "Réservation & Paiement" {
        useCase "Réserver une expérience" as UC14
        useCase "Payer mobile money" as UC15
        useCase "Payer sur place" as UC16
        useCase "Recevoir le billet QR" as UC17
        useCase "Scanner le QR (artisan)" as UC18
        useCase "Accepter/Refuser réservation" as UC19
        useCase "Marquer complétée" as UC20
    }

    package "Interaction" {
        useCase "Laisser un avis" as UC21
        useCase "Publier un moment (short)" as UC22
        useCase "Ajouter en favori" as UC23
        useCase "Discuter avec l'artisan (Bridge)" as UC24
        useCase "Écouter la voix de l'artisan" as UC25
    }

    package "Apprentissage (ƉƆKUN Learn)" {
        useCase "Parcourir les cours" as UC26
        useCase "Apprendre du vocabulaire" as UC27
        useCase "Jouer au quiz" as UC28
        useCase "Jouer au mini-jeu" as UC29
    }

    package "Espace Artisan (Mon Atelier)" {
        useCase "Gérer son profil" as UC30
        useCase "Gérer ses médias" as UC31
        useCase "Gérer ses voix" as UC32
        useCase "Gérer ses réservations" as UC33
        useCase "Recevoir des notifications" as UC34
    }

    package "Candidature" {
        useCase "Postuler comme artisan" as UC35
        useCase "Postuler comme acteur" as UC36
    }

    package "Administration" {
        useCase "Modérer les avis" as UC37
        useCase "Modérer les moments" as UC38
        useCase "Modérer les médias" as UC39
        useCase "Gérer les candidatures" as UC40
        useCase "Gérer les utilisateurs" as UC41
        useCase "Gérer les catégories" as UC42
        useCase "Gérer les expériences" as UC43
        useCase "Gérer les quartiers" as UC44
        useCase "Gérer les demandes d'acteurs" as UC45
        useCase "Voir le dashboard" as UC46
    }

    package "Système" {
        useCase "Auto-compléter réservations" as UC47
        useCase "Webhook paiement" as UC48
        useCase "Envoyer notifications email" as UC49
        useCase "Envoyer notifications WhatsApp" as UC50
        useCase "Attribuer badges" as UC51
        useCase "Calculer points fidélité" as UC52
    }

    ' === RELATIONS VISITEUR ===
    V --> UC1
    V --> UC2
    V --> UC3
    V --> UC4
    V --> UC5
    V --> UC6
    V --> UC7
    V --> UC8
    V --> UC9
    V --> UC10
    V --> UC11
    V --> UC12
    V --> UC36

    ' === RELATIONS TOURISTE ===
    T --|> V
    T --> UC14
    T --> UC17
    T --> UC21
    T --> UC22
    T --> UC23
    T --> UC24
    T --> UC25
    T --> UC26
    T --> UC27
    T --> UC28
    T --> UC29
    T --> UC35

    ' === RELATIONS ARTISAN ===
    A --|> T
    A --> UC18
    A --> UC19
    A --> UC20
    A --> UC30
    A --> UC31
    A --> UC32
    A --> UC33
    A --> UC34

    ' === RELATIONS ADMIN ===
    ADM --|> T
    ADM --> UC37
    ADM --> UC38
    ADM --> UC39
    ADM --> UC40
    ADM --> UC41
    ADM --> UC42
    ADM --> UC43
    ADM --> UC44
    ADM --> UC45
    ADM --> UC46

    ' === RELATIONS SYSTÈME ===
    S --> UC47
    S --> UC49
    S --> UC50
    S --> UC51
    S --> UC52

    ' === RELATIONS EXTERNES ===
    FP --> UC48
    UC14 ..> UC15 : <<include>>
    UC14 ..> UC16 : <<include>>
    UC14 ..> UC17 : <<include>>
    UC15 ..> UC48 : <<include>>
```

---

## Cas d'utilisation détaillés

### UC14 — Réserver une expérience

| Élément | Description |
|---------|-------------|
| **Acteur** | Touriste (authentifié) |
| **Pré-condition** | Touriste connecté, expérience publiée |
| **Scénario principal** | 1. Touriste sélectionne une expérience → 2. Remplit le formulaire (date, nb personnes, message) → 3. Choisit le mode de paiement → 4. Confirme → 5. Reçoit le billet QR |
| **Scénario alternatif** | 3a. Paiement mobile money → redirection FedaPay → callback succès/échec |
| **Post-condition** | Réservation créée (status: pending), paiement enregistré |

### UC18 — Scanner le QR (artisan)

| Élément | Description |
|---------|-------------|
| **Acteur** | Artisan |
| **Pré-condition** | Réservation existante avec QR token valide |
| **Scénario principal** | 1. Artisan ouvre le lien QR → 2. Voit les détails de la réservation → 3. Accepte ou refuse |
| **Scénario alternatif** | 3a. Accepte → status = accepted → notification visiteur → 4. Marque complétée → status = completed |
| **Post-condition** | Réservation mise à jour, visiteur notifié |

### UC24 — Discuter avec l'artisan (Bridge)

| Élément | Description |
|---------|-------------|
| **Acteur** | Touriste |
| **Pré-condition** | Touriste connecté, artisan vérifié |
| **Scénario principal** | 1. Touriste ouvre Bridge → 2. Envoie un message → 3. L'IA répond (ambassadeur culturel) → 4. Conversation continue |
| **Post-condition** | Historique sauvegardé, points fidélité attribués |

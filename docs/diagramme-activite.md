# Diagramme d'Activité — Flux Principaux ƉƆKUN

## 1. Flux Réservation Complet (Visiteur → Artisan → Complétion)

```mermaid
flowchart TD
    Start([Début: Touriste veut réserver]) --> A[Consulter les expériences]
    A --> B{Sélectionner une expérience}
    B --> C[Remplir formulaire réservation]
    C --> D{Choisir mode de paiement}
    D -->|Mobile Money| E[Redirection FedaPay]
    D -->|Sur place| F[Créer réservation]
    E --> G{Webhook FedaPay}
    G -->|Succès| F
    G -->|Échec| H[Afficher erreur paiement]
    H --> C

    F --> I[Générer QR code token]
    I --> J[Envoyer billet QR au visiteur]
    J --> K[Notifier l'artisan par email]

    K --> L{Artisan scanne le QR}
    L --> M[Voir détails réservation]
    M --> N{Décision artisan}
    N -->|Accepter| O[Status = accepted]
    N -->|Refuser| P[Status = rejected]
    P --> Q[Notifier visiteur refus]

    O --> R{Expérience réalisée ?}
    R -->|Oui| S[Artisan marque complétée]
    R -->|Non| T[Attente]

    S --> U[Status = completed]
    U --> V[Envoyer email confirmation visiteur]
    V --> W[Envoyer WhatsApp visiteur]
    W --> X[Inviter à laisser un avis]
    X --> Y[Inviter à publier un moment]
    Y --> Z[Attribuer badges XP]
    Z --> End([Fin])

    T --> S
    Q --> End2([Fin: Réservation refusée])
    H --> End3([Fin: Paiement échoué])

    style Start fill:#064E3B,color:#fff
    style End fill:#C99424,color:#fff
    style End2 fill:#dc3545,color:#fff
    style End3 fill:#dc3545,color:#fff
```

---

## 2. Flux Candidature Artisan

```mermaid
flowchart TD
    Start([Début: Touriste veut devenir artisan]) --> A[Se connecter]
    A --> B{Déjà vérifié ?}
    B -->|Non| C[Invitation à vérifier email]
    B -->|Oui| D[Accéder formulaire candidature]
    D --> E[Remplir informations personnelles]
    E --> F[Choisir métier / catégorie]
    F --> G[Décrire son savoir-faire]
    G --> H[Soumettre candidature]
    H --> I[Status = pending]
    I --> J[Email confirmation au candidat]
    J --> K{Admin modère}
    K -->|Approuver| L[Créer compte artisan]
    L --> M[Changer rôle vers artisan]
    M --> N[Email bienvenue + accès atelier]
    K -->|Rejeter| O[Email refus avec motifs]
    O --> P[Fin candidature]

    N --> Q[Artisan accède à Mon Atelier]
    Q --> R[Compléter profil professionnel]
    R --> S[Photo de profil]
    S --> T[Ajouter médias]
    T --> U[Créer expériences]
    U --> V[Profil en attente modération]
    V --> W{Admin approuve profil ?}
    W -->|Oui| X[Profil visible publiquement]
    W -->|Non| Y[Demande corrections]

    X --> End([Fin: Artisan actif])

    style Start fill:#064E3B,color:#fff
    style End fill:#C99424,color:#fff
    style P fill:#dc3545,color:#fff
```

---

## 3. Flux Apprentissage (ƉƆKUN Learn)

```mermaid
flowchart TD
    Start([Début: Touriste veut apprendre]) --> A[Consulter catalogue cours]
    A --> B[Sélectionner un cours]
    B --> C[Choisir une leçon]
    C --> D[Afficher flashcards]
    D --> E{Mode d'apprentissage}
    E -->|Flashcards| F[Parcourir cartes]
    F --> G[Écouter prononciation Fon/Gun]
    G --> H{Répété ?}
    H -->|Non| F
    H -->|Oui| I[Passer à la carte suivante]
    I --> F

    E -->|Quiz| J[Démarrer quiz]
    J --> K[Afficher question]
    K --> L{Réponse correcte ?}
    L -->|Oui| M[Score +1]
    L -->|Non| N[Afficher bonne réponse]
    M --> O{Dernière question ?}
    N --> O
    O -->|Non| K
    O -->|Oui| P[Afficher résultat final]

    P --> Q{Score >= 80% ?}
    Q -->|Oui| R[Marquer leçon complétée]
    Q -->|Non| S[Proposer réessayer]
    S --> C

    R --> T[Attribuer XP points]
    T --> U{Badges débloqués ?}
    U -->|Oui| V[Attribuer badge]
    U -->|Non| W[Mettre à jour progression]
    V --> W

    W --> X{Autre leçon ?}
    X -->|Oui| C
    X -->|Non| Y[Retour au catalogue]
    Y --> End([Fin])

    style Start fill:#064E3B,color:#fff
    style End fill:#C99424,color:#fff
```

---

## 4. Flux Bridge (Conversation IA)

```mermaid
flowchart TD
    Start([Début: Touriste ouvre Bridge]) --> A[Page conversation]
    A --> B{Première visite ?}
    B -->|Oui| C[Afficher message bienvenue]
    B -->|Non| D[Charger historique]
    C --> E[Touriste envoie message]
    D --> E

    E --> F[Envoyer à API IA]
    F --> G{Réponse IA}
    G --> H[Ambassadeur culturel répond]
    H --> I{Le message contient un mot Fon ?}
    I -->|Oui| J[Ajouter traduction]
    I -->|Non| K[Afficher réponse]
    J --> K

    K --> L{Le visiteur veut réserver ?}
    L -->|Oui| M[Proposer lien réservation]
    L -->|Non| N{Conversation terminée ?}
    N -->|Non| E
    N -->|Oui| O[Sauvegarder historique]
    M --> N

    O --> P[Attribuer points fidélité]
    P --> End([Fin])

    style Start fill:#064E3B,color:#fff
    style End fill:#C99424,color:#fff
```

---

## 5. Flux Paiement FedaPay

```mermaid
flowchart TD
    Start([Début: Paiement initié]) --> A[Créer enregistrement PendingPayment]
    A --> B[Initialiser transaction FedaPay]
    B --> C[Rediriger vers page paiement FedaPay]
    C --> D{Résultat paiement}
    D -->|Succès| E[Callback GET /payment/callback]
    D -->|Échec| F[Callback avec erreur]
    D -->|Annulé| G[Transaction expirée]

    E --> H[Vérifier token callback]
    H --> I{Transaction payée ?}
    I -->|Oui| J[Mettre à jour PendingPayment = completed]
    I -->|Non| K[Mettre à jour = failed]

    J --> L{Réservation associée ?}
    L -->|Oui| M[Mettre à jour payment_status = paid]
    L -->|Non| N[Créer réservation depuis données]

    M --> O[Générer QR code token]
    N --> O

    O --> P[Envoyer billet QR par email]
    P --> Q[Notifier l'artisan]
    Q --> End([Fin: Paiement complété])

    K --> R[Afficher erreur au visiteur]
    G --> R
    R --> End2([Fin: Paiement échoué])

    F --> S[Webhook FedaPay]
    S --> T{Vérifier signature}
    T -->|Valide| U[Mettre à jour statut]
    T -->|Invalide| V[Rejeter webhook]

    style Start fill:#064E3B,color:#fff
    style End fill:#C99424,color:#fff
    style End2 fill:#dc3545,color:#fff
```

---

## 6. Flux Modération Admin

```mermaid
flowchart TD
    Start([Début: Contenu soumis]) --> A{Type de contenu}
    A -->|Avis| B[File d'attente modération]
    A -->|Moment| B
    A -->|Média| B
    A -->|Candidature artisan| C[File candidatures]
    A -->|Demande acteur| D[File demandes]

    B --> E[Admin ouvre la modération]
    E --> F{Décision}
    F -->|Approuver| G[Status = published]
    F -->|Rejeter| H[Status = rejected]
    F -->|Demander correction| I[Status = pending + note]

    G --> J[Notification auteur]
    H --> K[Notification rejet + motifs]
    I --> L[Notification correction]

    C --> M[Admin review candidature]
    M --> N{Décision}
    N -->|Approuver| O[Créer artisan + changer rôle]
    N -->|Rejeter| P[Email refus]

    O --> Q[Email bienvenue]
    Q --> R[Artisan accède Mon Atelier]

    D --> S[Admin review demande]
    S --> T{Décision}
    T -->|Approuver| U[Créer compte + changer rôle]
    T -->|Rejeter| V[Email refus]

    style Start fill:#064E3B,color:#fff
    style G fill:#28a745,color:#fff
    style H fill:#dc3545,color:#fff
```

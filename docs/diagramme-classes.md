# Diagramme de Classes — Plateforme ƉƆKUN

## Diagramme UML (Mermaid)

```mermaid
classDiagram
    direction TB

    class User {
        +BigInt id
        +String name
        +String email
        +String password
        +String role [tourist|artisan|admin|...]
        +DateTime email_verified_at
        +DateTime created_at
        +DateTime updated_at
        +isAdmin() bool
        +isArtisan() bool
        +isTourist() bool
        +homeRoute() String
    }

    class Artisan {
        +BigInt id
        +BigInt category_id FK
        +BigInt user_id FK
        +String first_name
        +String last_name
        +String professional_name
        +String photo_path
        +String phone
        +String whatsapp
        +Text description
        +Text history
        +Int experience_years
        +String address
        +Decimal latitude
        +Decimal longitude
        +JSON pending_profile_data
        +Enum status [draft|pending|published]
        +getImageUrlAttribute() String
    }

    class Category {
        +BigInt id
        +String name
        +String slug
        +Text description
    }

    class SavoirFaire {
        +BigInt id
        +BigInt category_id FK
        +String name
        +String slug
        +Text description
        +String image_path
    }

    class Experience {
        +BigInt id
        +BigInt artisan_id FK
        +String title
        +Text summary
        +UnsignedSmallInt duration_minutes
        +UnsignedSmallInt capacity
        +UnsignedInt price
        +String currency
        +String language
        +String image_path
        +Boolean is_published
    }

    class ReservationRequest {
        +BigInt id
        +BigInt artisan_id FK
        +BigInt experience_id FK
        +BigInt user_id FK
        +String visitor_name
        +String visitor_phone
        +String visitor_email
        +Date requested_date
        +Int guests_count
        +String experience_type
        +Text message
        +UnsignedInt total_amount
        +UnsignedInt service_fee
        +String currency
        +Enum payment_method [pay_on_site|mobile_money]
        +Enum payment_status [not_required|pending|paid|failed]
        +String reference
        +String fedapay_transaction_id
        +String qr_code_token
        +Enum status [pending|accepted|rejected|completed]
    }

    class Review {
        +BigInt id
        +BigInt user_id FK
        +BigInt artisan_id FK
        +BigInt reservation_request_id FK
        +UnsignedTinyInt rating
        +Text comment
        +Enum status [pending|published|rejected]
        +BigInt moderated_by FK
        +DateTime moderated_at
    }

    class Media {
        +BigInt id
        +BigInt artisan_id FK
        +Enum type [image|video_url]
        +Enum status [pending|published|rejected]
        +String path
        +String title
        +Text description
        +BigInt moderated_by FK
        +DateTime moderated_at
    }

    class Moment {
        +BigInt id
        +BigInt user_id FK
        +BigInt reservation_request_id FK
        +BigInt artisan_id FK
        +String title
        +Text description
        +String video_path
        +String cover_path
        +String status
        +String share_token
        +BigInt moderated_by FK
        +DateTime moderated_at
        +getVideoUrlAttribute() String
        +getCoverUrlAttribute() String
    }

    class Badge {
        +BigInt id
        +String code
        +String name_fr
        +String name_en
        +String desc_fr
        +String desc_en
        +String icon
    }

    class LoyaltyEvent {
        +BigInt id
        +BigInt user_id FK
        +String code
        +UnsignedInt points
        +JSON meta
    }

    class LoyaltySummary {
        +BigInt id
        +BigInt user_id FK
        +UnsignedInt total_points
        +UnsignedSmallInt streak_days
        +Date last_activity_date
    }

    class LearnCourse {
        +BigInt id
        +String slug
        +String title_fr
        +String title_en
        +String desc_fr
        +String desc_en
        +String icon
        +String accent
        +UnsignedInt sort_order
    }

    class LearnLesson {
        +BigInt id
        +BigInt course_id FK
        +String slug
        +String title_fr
        +String title_en
        +UnsignedInt sort_order
    }

    class LearnWord {
        +BigInt id
        +BigInt lesson_id FK
        +String local_word
        +String french_translation
        +String english_translation
        +String context
        +UnsignedInt sort_order
    }

    class LearnProgress {
        +BigInt id
        +BigInt user_id FK
        +BigInt lesson_id FK
        +UnsignedTinyInt best_score
        +DateTime completed_at
    }

    class ArtisanApplication {
        +BigInt id
        +BigInt user_id FK
        +String first_name
        +String last_name
        +String professional_name
        +String phone
        +String whatsapp
        +Text description
        +Text history
        +Int experience_years
        +String address
        +BigInt category_id FK
        +String trade
        +Enum status [pending|approved|rejected]
        +Text admin_notes
        +BigInt reviewed_by FK
        +DateTime reviewed_at
    }

    class ActorRequest {
        +BigInt id
        +Enum role [guide|institution|researcher|partner]
        +String name
        +String email
        +String phone
        +String organization
        +Text motivation
        +JSON extra_data
        +Enum status [pending|approved|rejected]
        +Text admin_notes
        +BigInt reviewed_by FK
        +DateTime reviewed_at
    }

    class ArtisanFavorite {
        +BigInt id
        +BigInt user_id FK
        +BigInt artisan_id FK
    }

    class PendingPayment {
        +BigInt id
        +String reference
        +JSON reservation_data
        +String fedapay_transaction_id
        +Enum status [pending|completed|expired|failed]
    }

    class Quartier {
        +BigInt id
        +String name
        +String slug
        +Decimal lat
        +Decimal lng
        +Decimal radius_km
        +String color
        +Int sort_order
    }

    class Product {
        +BigInt id
        +BigInt artisan_id FK
        +String name
        +Text description
        +Decimal price
        +String image_path
        +Boolean is_available
    }

    ' ═══════════════════════════════════════════
    ' RELATIONS
    ' ═══════════════════════════════════════════

    User "1" -- "1" Artisan : possède >
    User "1" -- "*" ReservationRequest : effectue >
    User "1" -- "*" ArtisanFavorite : crée >
    User "1" -- "*" LoyaltyEvent : génère >
    User "1" -- "1" LoyaltySummary : possède >
    User "1" -- "*" Review : écrit >
    User "1" -- "*" Moment : publie >
    User "1" -- "*" LearnProgress : progresse >
    User "*" -- "*" Badge : débloque >

    Artisan "1" -- "*" Category : appartient >
    Artisan "1" -- "*" Experience : propose >
    Artisan "1" -- "*" ReservationRequest : reçoit >
    Artisan "1" -- "*" Review : reçoit >
    Artisan "1" -- "*" Media : possède >
    Artisan "1" -- "*" Moment : concerne >
    Artisan "*" -- "*" SavoirFaire : pratique >

    Category "1" -- "*" SavoirFaire : contient >

    Experience "1" -- "*" ReservationRequest : génère >

    ReservationRequest "1" -- "0..1" Moment : lie >
    ReservationRequest "1" -- "0..1" Review : lie >

    LearnCourse "1" -- "*" LearnLesson : contient >
    LearnLesson "1" -- "*" LearnWord : contient >
    LearnLesson "1" -- "*" LearnProgress : suit >

    ArtisanApplication "*" -- "1" User : candidat >
    ArtisanApplication "*" -- "0..1" Category : vise >
    ArtisanApplication "*" -- "0..1" User : examine >

    ActorRequest "*" -- "0..1" User : examine >

    ArtisanFavorite "*" -- "1" Artisan : concerne >

    Product "*" -- "1" Artisan : appartient >

    PendingPayment ..> ReservationRequest : crée si succès
    Moment ..> ReservationRequest : liée à
```

---

## Tableau récapitulatif des relations

| Relation | Cardinalité | Type | Description |
|----------|-------------|------|-------------|
| User → Artisan | 1..1 | hasOne | Un user peut devenir artisan |
| User → ReservationRequest | 1..* | hasMany | Un tourist fait plusieurs réservations |
| User → Badge | *..* | belongsToMany | Un user débloque plusieurs badges |
| User → LoyaltySummary | 1..1 | hasOne | Un user a un résumé de fidélité |
| User → LearnProgress | 1..* | hasMany | Un user progresse dans plusieurs leçons |
| Artisan → Category | *..1 | belongsTo | Un artisan appartient à une catégorie |
| Artisan → Experience | 1..* | hasMany | Un artisan propose plusieurs expériences |
| Artisan → SavoirFaire | *..* | belongsToMany | Un artisan pratique plusieurs savoir-faires |
| Artisan → ReservationRequest | 1..* | hasMany | Un artisan reçoit plusieurs réservations |
| Artisan → Review | 1..* | hasMany | Un artisan reçoit plusieurs avis |
| Artisan → Media | 1..* | hasMany | Un artisan a plusieurs médias |
| Category → SavoirFaire | 1..* | hasMany | Une catégorie contient plusieurs savoir-faires |
| Experience → ReservationRequest | 1..* | hasMany | Une expérience génère plusieurs réservations |
| LearnCourse → LearnLesson | 1..* | hasMany | Un cours contient plusieurs leçons |
| LearnLesson → LearnWord | 1..* | hasMany | Une leçon contient plusieurs mots |
| LearnProgress → User + LearnLesson | *..* | belongsTo | Suivi de progression |

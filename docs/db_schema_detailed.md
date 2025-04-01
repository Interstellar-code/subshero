# Detailed Database Schema

## Core Tables

```mermaid
erDiagram
    users {
        int id PK
        varchar(255) name
        varchar(255) email
        varchar(255) password
        datetime created_at
        datetime updated_at
        tinyint status
    }
    
    subscriptions {
        int id PK
        int user_id FK
        int brand_id FK
        decimal amount
        date start_date
        date end_date
        varchar(20) frequency
        tinyint status
    }
    
    brands {
        int id PK
        varchar(100) name
        varchar(255) description
        varchar(100) url
        tinyint status
    }
    
    plans {
        int id PK
        varchar(50) name
        decimal price
        smallint duration_days
        text description
    }
    
    users_alert {
        int id PK
        int user_id FK
        tinyint is_default
        int time_period
        time alert_time
    }
    
    users_contacts {
        int id PK
        int user_id FK
        varchar(50) name
        varchar(50) email
        tinyint status
    }
```

## Complete Table List
1. users
2. subscriptions
3. brands
4. plans
5. users_alert
6. users_contacts
7. users_payment_methods
8. subscriptions_tags
9. email_templates
10. email_types
11. events
12. event_types
13. event_emails
14. event_browser
15. event_webhook
16. files
17. folder
18. tags
19. config
20. settings
[...remaining tables...]

## Key Relationships
- users 1→N subscriptions
- users 1→N users_alert
- users 1→N users_contacts
- brands 1→N subscriptions
- plans 1→N brands
- subscriptions N→M tags (through subscriptions_tags)
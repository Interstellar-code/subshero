# Database Schema Overview

## System Architecture
The database schema is organized around three main domains:
1. **User Management**: Handles user accounts, profiles, and preferences
2. **Subscription Management**: Manages subscriptions, brands, and plans
3. **Notification System**: Handles alerts, emails, and notifications

```mermaid
erDiagram
    users ||--o{ subscriptions : "has"
    users ||--o{ users_alert : "configures"
    users ||--o{ users_contacts : "maintains"
    users ||--o{ users_payment_methods : "stores"
    users ||--o{ users_profile : "has"
    subscriptions ||--o{ subscriptions_tags : "categorizes"
    subscriptions }|--|| brands : "belongs to"
    brands }|--|| plans : "offers"
    plans }|--o{ plan_coupons : "has"
    email_templates }|--|| email_types : "implements"
    events ||--o{ event_types : "categorizes"
```

## Core Entities

### Users
- Central entity for all user-related data
- Manages authentication, profiles, and preferences
- Related to subscriptions, alerts, and contacts

### Subscriptions
- Represents user subscriptions to brands/services
- Tracks payment details, duration, and status
- Categorized through tags and associated with brands

### Brands
- Service providers offering subscription plans
- Each brand has one active plan
- Contains brand details and subscription options

### Plans
- Defines subscription pricing and features
- Can have multiple coupons for discounts
- Determines subscription duration and benefits

## Key Relationships
1. **User-Subscription**: One user can have multiple subscriptions
2. **Subscription-Brand**: Each subscription belongs to one brand
3. **Brand-Plan**: Each brand offers one plan
4. **User-Alerts**: Users can configure multiple alert profiles
5. **Subscription-Tags**: Subscriptions can be categorized with multiple tags
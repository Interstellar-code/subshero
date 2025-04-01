# Subscription Calculation Requirements

## 1. Introduction and Purpose

This document defines the requirements for implementing subscription calculation functionality in the Subshero subscription tracking application. It outlines the key database tables involved, the relationships between them, and the business rules for calculating subscription costs.

The primary goal is to establish a consistent and accurate method for:
- Calculating recurring subscription costs based on different frequencies
- Handling prorated calculations for partial subscription periods
- Ensuring the system can accommodate various billing cycles and payment scenarios

## 2. Key Tables and Relationships

Based on the database schema analysis, the following tables are central to subscription calculations:

### 2.1 Core Tables

#### Subscriptions Table
The `subscriptions` table is the primary entity for subscription data with the following key fields for calculations:
- `price`: The base price of the subscription
- `recurring`: Whether the subscription is recurring (0=No, 1=Yes)
- `payment_date`: The date of the initial payment
- `next_payment_date`: The date of the next scheduled payment
- `expiry_date`: The date when the subscription expires
- `billing_frequency`: The frequency of billing
- `billing_cycle`: The cycle of billing (1=Daily, 2=Weekly, 3=Monthly, 4=Yearly)
- `billing_type`: How billing is calculated (1=Calculate by days, 2=Calculate by date)
- `currency_code`: The currency code for the subscription

#### Brands Table
The `brands` table contains information about service providers with these relevant fields:
- `subs_price`: The subscription price set by the brand
- `subs_frequency`: The frequency of subscription
- `subs_currency`: The currency for the subscription
- `subs_cycle`: The cycle for the subscription

#### Plans Table
The `plans` table defines subscription pricing and features:
- `price_monthly`: The monthly price of the plan
- `price_annually`: The annual price of the plan
- `ltd_price`: The lifetime deal price
- `ltd_price_date`: The expiration date for the lifetime deal price

### 2.2 Key Relationships

1. **User-Subscription**: One user can have multiple subscriptions (users.id → subscriptions.user_id)
2. **Subscription-Brand**: Each subscription belongs to one brand (brands.id → subscriptions.brand_id)
3. **Brand-Plan**: Each brand is associated with a plan (implied relationship in ER diagrams)

## 3. Subscription Calculation Requirements

### 3.1 Base Subscription Cost Calculations

The system must calculate subscription costs based on the following factors:

1. **Billing Cycle**: The subscription cost varies based on the billing cycle:
   - Daily: Calculate cost per day
   - Weekly: Calculate cost per week
   - Monthly: Calculate cost per month
   - Yearly: Calculate cost per year

2. **Recurring vs. One-time**: 
   - Recurring subscriptions should automatically calculate the next payment date based on the billing cycle
   - One-time payments should not generate future payment dates

3. **Currency Handling**:
   - All calculations should respect the currency specified in the subscription
   - The system should store both the original currency amount and a base currency amount for reporting

### 3.2 Proration Rules

Different proration rules apply based on the billing cycle:

1. **Monthly Subscriptions**:
   - Prorate by day
   - Formula: (Monthly Price ÷ Days in Month) × Days Remaining in Month

2. **Yearly Subscriptions**:
   - Prorate by month
   - Formula: (Yearly Price ÷ 12) × Months Remaining in Year

3. **Weekly Subscriptions**:
   - No proration
   - Charge the full weekly amount regardless of start date within the week

### 3.3 Calculation Scenarios

The system must handle the following calculation scenarios:

1. **New Subscription**:
   - Calculate initial payment amount (prorated if applicable)
   - Set next payment date based on billing cycle

2. **Subscription Renewal**:
   - Calculate full payment amount for the next billing cycle
   - Update next payment date

3. **Subscription Upgrade/Downgrade**:
   - Calculate prorated refund for remaining time on current plan
   - Calculate prorated charge for new plan
   - Determine net charge or refund

4. **Subscription Cancellation**:
   - Determine if refund is applicable based on cancellation date and refund policy
   - Calculate prorated refund amount if applicable

## 4. Business Rules and Implementation Guidelines

### 4.1 General Rules

1. **Date Calculations**:
   - All date calculations should use the timezone specified in the subscription
   - For monthly calculations, respect the varying number of days in different months
   - For yearly calculations, account for leap years appropriately

2. **Rounding Rules**:
   - All monetary calculations should round to 2 decimal places
   - Rounding should occur only at the final step of calculations
   - Use standard rounding rules (round half up)

3. **Payment Date Determination**:
   - For new subscriptions, the payment date is the subscription start date
   - For renewals, the payment date is the day after the current subscription period ends
   - If a specific day of month is required, adjust the payment date accordingly

### 4.2 Implementation Considerations

1. **Calculation Service**:
   - Implement a dedicated service for subscription calculations
   - Ensure the service is reusable across different parts of the application

2. **Audit Trail**:
   - Log all calculation details for audit purposes
   - Store the calculation parameters and results in the subscription history

3. **Error Handling**:
   - Implement robust error handling for edge cases
   - Provide clear error messages for calculation failures

4. **Performance Considerations**:
   - Optimize calculations for performance, especially for bulk operations
   - Consider caching frequently used calculation results

### 4.3 Future Enhancements

While not part of the current implementation, the following enhancements should be considered for future versions:

1. **Discount and Coupon Integration**:
   - Support for percentage and fixed amount discounts
   - Rules for applying discounts to different billing cycles

2. **Tax Calculations**:
   - Integration with tax calculation services
   - Support for different tax rules based on location

3. **Multi-currency Support**:
   - Real-time currency conversion
   - Historical exchange rate tracking
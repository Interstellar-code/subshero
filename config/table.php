<?php
return [
    'subscription' => [
        'type' => [
            1 => 'Subscription',
            2 => 'Trial',
            3 => 'Lifetime',
            // 4 => 'Revenue',
        ],
        'cycle' => [
            1 => 'Day',
            2 => 'Week',
            3 => 'Month',
            4 => 'Year',
        ],
        'cycle_ly' => [
            1 => 'Daily',
            2 => 'Weekly',
            3 => 'Monthly',
            4 => 'Yearly',
        ],
        'recurring' => [
            'Once',
            'Recurring',
        ],
        'alert' => [
            'type' => [
                1 => 'System Default',
                0 => 'No Alert',
                // 2 => 'My alert Profile',
            ],
        ],
        'price' => [
            'type' => [
                'USD' => 'USD',
                'EUR' => 'EUR',
                'INR' => 'INR',
            ],
        ],
        'payment' => [
            'mode' => [
                1 => 'PayPal',
                2 => 'Credit Card',
                3 => 'Free',
            ],
        ],
        'payment_type' => [
            'PayPal',
            'Credit Card',
            'Others',
        ],
        'billing_type' => [
            1 => 'days',
            2 => 'date',
        ],
        'billing_type_uppercase' => [
            1 => 'Days',
            2 => 'Date',
        ],
        'status' => [
            0 => 'Draft',
            1 => 'Active',
            2 => 'Cancel',
            3 => 'Refund',
            4 => 'Expired',
            // 4 => 'Pause',
        ],
        'status_lowercase' => [
            0 => 'draft',
            1 => 'active',
            2 => 'cancel',
            3 => 'refund',
            4 => 'expired',
            // 4 => 'Pause',
        ],

        'rating' => [
            2 => 'Very Bad',
            4 => 'Bad',
            6 => 'Average',
            8 => 'Good',
            10 => 'Excellent',
        ],
        'sub_addon' => [
            'Subscription',
            'Addon',
        ],
    ],


    'product' => [
        'status' => [
            0 => 'Inactive',
            1 => 'Active',
            2 => 'User submitted',
        ],
        'product_type' => [
            1 => 'SaaS',
            2 => 'WordPress',
            3 => 'Desktop app',
        ],
        'pricing_type' => [
            1 => 'Subscription',
            2 => 'Trial',
            3 => 'Lifetime',
            // 4 => 'Revenue',
        ],
        'currency_code' => [
            'USD' => 'USD',
            'EUR' => 'EUR',
            'INR' => 'INR',
        ],
    ],


    'email_templates' => [
        'is_default' => [
            0 => 'No',
            1 => 'Yes',
        ],
        'status' => [
            0 => 'Disabled',
            1 => 'Enabled',
        ],
    ],


    'users_alert_preferences' => [
        'time_cycle' => [
            1 => 'Before Due Date',
            // 2 => 'After Due Date',
            3 => 'Before Refund Date',
        ],
    ],


    'users_alert' => [
        'time_cycle' => [
            1 => 'Days Before',
        ],
        'alert_condition' => [
            1 => 'All',
            2 => 'Before Due Date',
            3 => 'Before Refund Date',
        ],
        'alert_type' => [
            1 => 'All',
            2 => 'Email',
            3 => 'Browser',
        ],
        'alert_subs_type' => [
            1 => 'Subscription',
            2 => 'Trial',
            3 => 'Lifetime',
            // 4 => 'Revenue',
        ],
        'time_period_cycle' => [
            1 => 'Day',
            2 => 'Week',
            3 => 'Month',
            // 4 => 'Year',
        ],
        'is_default' => [
            0 => 'No',
            1 => 'Yes',
        ],
    ],


    'users_teams' => [
        'status' => [
            1 => 'Sent',
            2 => 'Accepted',
        ],
    ],


    'webhooks' => [
        'events' => [
            'subscription.created',
            'subscription.updated',
            'subscription.deleted',
            'subscription.refunded',
            'subscription.canceled',
        ],
        'status' => [
            0 => 'Inactive',
            1 => 'Active',
        ],
        'type' => [
            1 => 'Incoming',
            2 => 'Outgoing',
        ],
    ],
];

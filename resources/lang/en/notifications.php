<?php

return [

    // ── Welcome / Account creation ─────────────────────────────────────────────
    'welcome_subject' => 'Your ML Sourcing account is ready',
    'welcome_message' => "Hello :name,\n\nYour account has been created successfully.\nEmail: :email\nPassword: :password\n\nPlease log in and change your password as soon as possible.",
    'welcome_sms'     => 'ML Sourcing: Your account has been created. Log in with email :email.',

    // ── Account activated ──────────────────────────────────────────────────────
    'account_activated_subject' => 'Your ML Sourcing account has been activated',
    'account_activated_message' => "Hello :name,\n\nYour account has been activated. You can now log in and use the platform.",
    'account_activated_sms'     => 'ML Sourcing: Your account has been activated. Log in now.',

    // ── Account unblocked ──────────────────────────────────────────────────────
    'account_unblocked_subject' => 'Your ML Sourcing account has been unblocked',
    'account_unblocked_message' => "Hello :name,\n\nYour account has been unblocked. You now have access to the platform again.",
    'account_unblocked_sms'     => 'ML Sourcing: Your account has been unblocked. You can log in again.',

    // ── Payment approved — seller ──────────────────────────────────────────────
    'payment_approved_seller_subject' => 'Payment approved ✓',
    'payment_approved_seller_message' => "Congratulations!\n\nYour payment #:payment_id has been approved. Your order is now being processed.",
    'payment_approved_seller_sms'     => 'ML Sourcing: Payment #:payment_id approved. Your order is in progress.',

    // ── Payment rejected — seller ──────────────────────────────────────────────
    'payment_rejected_seller_subject' => 'Payment rejected',
    'payment_rejected_seller_message' => "Your payment #:payment_id has been rejected.\n\nPlease check your request details for more information.",
    'payment_rejected_seller_sms'     => 'ML Sourcing: Payment #:payment_id rejected. Check your account.',

    // ── Payment approved — agent ───────────────────────────────────────────────
    'payment_approved_agent_subject' => 'Payment approved – shipping required',
    'payment_approved_agent_message' => "Payment #:payment_id has been approved.\n\nPlease proceed with shipping the product as soon as possible.",
    'payment_approved_agent_sms'     => 'ML Sourcing: Payment #:payment_id approved. Please ship the product.',

    // ── Payment rejected — agent ───────────────────────────────────────────────
    'payment_rejected_agent_subject' => 'Payment rejected',
    'payment_rejected_agent_message' => "Payment #:payment_id has been rejected.",
    'payment_rejected_agent_sms'     => 'ML Sourcing: Payment #:payment_id rejected.',

    // ── New request — agent ────────────────────────────────────────────────────
    'new_request_agent_subject' => 'New request assigned to you',
    'new_request_agent_message' => "A new request has been assigned to you.\n\nPlease check the details and proceed with the quote.",
    'new_request_agent_sms'     => 'ML Sourcing: A new request has been assigned to you.',

    // ── Request quoted — seller ────────────────────────────────────────────────
    'request_quoted_seller_subject' => 'Your request has been quoted',
    'request_quoted_seller_message' => "Your request #:request_no has been quoted.\n\nPlease review the quote and proceed with payment to continue.",
    'request_quoted_seller_sms'     => 'ML Sourcing: Request #:request_no has been quoted. Please make the payment.',

    // ── Product status updated — seller ───────────────────────────────────────
    'product_updated_seller_subject' => 'Your order status has been updated',
    'product_updated_seller_message' => "The product status for your request #:request_no has been updated.\n\nCheck the details for more information.",
    'product_updated_seller_sms'     => 'ML Sourcing: Request #:request_no – status updated. Check your account.',

    // ── Product status updated — admin ─────────────────────────────────────────
    'product_updated_admin_subject' => 'Product status updated',
    'product_updated_admin_message' => "The product status for request #:request_no has been updated.",
    'product_updated_admin_sms'     => 'ML Sourcing: Request #:request_no – status updated.',

    // ── Payment submitted — agent ──────────────────────────────────────────────
    'payment_submitted_agent_subject' => 'New payment to review',
    'payment_submitted_agent_message' => "A seller has submitted a payment for request #:request_no.\n\nPlease review it.",
    'payment_submitted_agent_sms'     => 'ML Sourcing: Payment received for request #:request_no. Please review.',

    // ── Payment submitted — admin ──────────────────────────────────────────────
    'payment_submitted_admin_subject' => 'New payment to review',
    'payment_submitted_admin_message' => "A seller has submitted a payment for request #:request_no.\n\nPlease review it in the payments section.",
    'payment_submitted_admin_sms'     => 'ML Sourcing: Payment received for request #:request_no.',

    // ── New request created — seller (by agent or admin) ──────────────────────
    'new_request_seller_subject' => 'A new request has been created for you',
    'new_request_seller_message' => "A new request has been created for you.\n\nPlease check the details of your request.",
    'new_request_seller_sms'     => 'ML Sourcing: A new request has been created for you. Check your account.',

    // ── New request without agent — admin ─────────────────────────────────────
    'new_request_admin_subject' => 'New request — no agent assigned',
    'new_request_admin_message' => "A new request #:request_no has been submitted but no agent matches the country pair (sourcing: :country_from → destination: :country_to).\n\nPlease assign an agent manually.",
    'new_request_admin_sms'     => 'ML Sourcing: Request #:request_no has no agent. Please assign one manually.',

    // ── New chat message ───────────────────────────────────────────────────────
    'new_chat_message_subject' => 'New message',
    'new_chat_message_message' => "You have received a new message from :sender_name regarding your request.\n\nLog in to read and reply.",
    'new_chat_message_sms'     => 'ML Sourcing: New message from :sender_name. Log in to reply.',

];

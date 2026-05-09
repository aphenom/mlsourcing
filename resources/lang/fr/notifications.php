<?php

return [

    // ── Bienvenue / Création de compte ─────────────────────────────────────────
    'welcome_subject' => 'Votre compte ML Sourcing est prêt',
    'welcome_message' => "Bonjour :name,\n\nVotre compte a été créé avec succès.\nEmail : :email\nMot de passe : :password\n\nConnectez-vous et changez votre mot de passe dès que possible.",
    'welcome_sms'     => 'ML Sourcing : Votre compte a été créé. Connectez-vous avec l\'email :email.',

    // ── Compte activé ──────────────────────────────────────────────────────────
    'account_activated_subject' => 'Votre compte ML Sourcing a été activé',
    'account_activated_message' => "Bonjour :name,\n\nVotre compte a été activé. Vous pouvez maintenant vous connecter et utiliser la plateforme.",
    'account_activated_sms'     => 'ML Sourcing : Votre compte a été activé. Connectez-vous maintenant.',

    // ── Compte débloqué ────────────────────────────────────────────────────────
    'account_unblocked_subject' => 'Votre compte ML Sourcing a été débloqué',
    'account_unblocked_message' => "Bonjour :name,\n\nVotre compte a été débloqué. Vous avez de nouveau accès à la plateforme.",
    'account_unblocked_sms'     => 'ML Sourcing : Votre compte a été débloqué. Reconnectez-vous.',

    // ── Paiement approuvé — vendeur ────────────────────────────────────────────
    'payment_approved_seller_subject' => 'Paiement approuvé ✓',
    'payment_approved_seller_message' => "Félicitations !\n\nVotre paiement n°:payment_id a été approuvé. Votre commande est en cours de traitement.",
    'payment_approved_seller_sms'     => 'ML Sourcing : Votre paiement n°:payment_id est approuvé. Votre commande est en cours.',

    // ── Paiement rejeté — vendeur ──────────────────────────────────────────────
    'payment_rejected_seller_subject' => 'Paiement rejeté',
    'payment_rejected_seller_message' => "Votre paiement n°:payment_id a été rejeté.\n\nConsultez les détails de votre demande pour en savoir plus.",
    'payment_rejected_seller_sms'     => 'ML Sourcing : Votre paiement n°:payment_id a été rejeté. Consultez votre espace.',

    // ── Paiement approuvé — agent ──────────────────────────────────────────────
    'payment_approved_agent_subject' => 'Paiement approuvé – expédition requise',
    'payment_approved_agent_message' => "Le paiement n°:payment_id a été approuvé.\n\nVeuillez procéder à l'expédition du produit dès que possible.",
    'payment_approved_agent_sms'     => 'ML Sourcing : Paiement n°:payment_id approuvé. Procédez à l\'expédition.',

    // ── Paiement rejeté — agent ────────────────────────────────────────────────
    'payment_rejected_agent_subject' => 'Paiement rejeté',
    'payment_rejected_agent_message' => "Le paiement n°:payment_id a été rejeté.",
    'payment_rejected_agent_sms'     => 'ML Sourcing : Paiement n°:payment_id rejeté.',

    // ── Nouvelle demande — agent ───────────────────────────────────────────────
    'new_request_agent_subject' => 'Nouvelle demande assignée',
    'new_request_agent_message' => "Une nouvelle demande vous a été assignée.\n\nConsultez les détails et procédez au chiffrage.",
    'new_request_agent_sms'     => 'ML Sourcing : Nouvelle demande assignée. Vérifiez votre espace.',

    // ── Demande chiffrée — vendeur ─────────────────────────────────────────────
    'request_quoted_seller_subject' => 'Votre demande a été chiffrée',
    'request_quoted_seller_message' => "Votre demande n°:request_no a été chiffrée.\n\nConsultez le devis et effectuez le paiement pour continuer.",
    'request_quoted_seller_sms'     => 'ML Sourcing : Demande n°:request_no chiffrée. Procédez au paiement.',

    // ── Statut produit mis à jour — vendeur ────────────────────────────────────
    'product_updated_seller_subject' => 'Statut de votre commande mis à jour',
    'product_updated_seller_message' => "Le statut du produit de votre demande n°:request_no a été mis à jour.\n\nConsultez les détails.",
    'product_updated_seller_sms'     => 'ML Sourcing : Demande n°:request_no – statut mis à jour. Vérifiez votre espace.',

    // ── Statut produit mis à jour — admin ──────────────────────────────────────
    'product_updated_admin_subject' => 'Statut produit mis à jour',
    'product_updated_admin_message' => "Le statut du produit de la demande n°:request_no a été mis à jour.",
    'product_updated_admin_sms'     => 'ML Sourcing : Demande n°:request_no – statut mis à jour.',

    // ── Paiement soumis — agent ────────────────────────────────────────────────
    'payment_submitted_agent_subject' => 'Nouveau paiement à vérifier',
    'payment_submitted_agent_message' => "Un vendeur a soumis un paiement pour la demande n°:request_no.\n\nVeuillez le vérifier.",
    'payment_submitted_agent_sms'     => 'ML Sourcing : Paiement reçu pour la demande n°:request_no. À vérifier.',

    // ── Paiement soumis — admin ────────────────────────────────────────────────
    'payment_submitted_admin_subject' => 'Nouveau paiement à vérifier',
    'payment_submitted_admin_message' => "Un vendeur a soumis un paiement pour la demande n°:request_no.\n\nVeuillez le vérifier dans la section paiements.",
    'payment_submitted_admin_sms'     => 'ML Sourcing : Paiement reçu pour la demande n°:request_no.',

    // ── Nouvelle requête créée — vendeur (par agent ou admin) ─────────────────
    'new_request_seller_subject' => 'Nouvelle demande créée pour vous',
    'new_request_seller_message' => "Une nouvelle demande a été créée pour vous.\n\nConsultez les détails de votre requête.",
    'new_request_seller_sms'     => 'ML Sourcing : Une nouvelle demande a été créée pour vous. Vérifiez votre espace.',

    // ── Nouveau message chat ───────────────────────────────────────────────────
    'new_chat_message_subject' => 'Nouveau message',
    'new_chat_message_message' => "Vous avez reçu un nouveau message de :sender_name concernant votre demande.\n\nConnectez-vous pour lire et répondre.",
    'new_chat_message_sms'     => 'ML Sourcing : Nouveau message de :sender_name. Connectez-vous pour répondre.',

];

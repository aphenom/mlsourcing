<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'choix_langue' => 'Change language',
    'choix_devise' => 'Change currency',


    /*
    |--------------------------------------------------------------------------
    | Population Statistics Language Lines
    |--------------------------------------------------------------------------
    */

    'voir' => 'SEE',
    'evo_mens_effectif_title' => 'Monthly Evolution of Headcounts',
    'mois' => 'Month',
    'janvier' => 'January',
    'fevrier' => 'February',
    'mars' => 'March',
    'avril' => 'April',
    'mai' => 'May',
    'juin' => 'June',
    'juillet' => 'July',
    'aout' => 'August',
    'septembre' => 'September',
    'octobre' => 'October',
    'novembre' => 'November',
    'decembre' => 'December',
    'effectif' => 'Headcount',
    'adherents' => 'Members',
    'conjoints' => 'Spouses',
    'enfants' => 'Children',
    'total' => 'Total',
    'nombre_effectif' => "Number of headcount",

    /*
    |--------------------------------------------------------------------------
    | Age Pyramid
    |--------------------------------------------------------------------------
    */

    'pyramid_age_adh_n1_title' => "Age Pyramid of Members Year N-1",
    'pyramid_age_adh_n_title' => "Age Pyramid of Members Year N",
    'pyramide_ages_population_totale_n1' => 'Age Pyramid of Total Population Year N-1',
    'pyramide_ages_population_totale' => 'Age Pyramid of Total Population Year N',
    'ans' => "years",
    'homme' => "Man",
    'femme' => "Woman",

    /*
    |--------------------------------------------------------------------------
    | Error Messages and Notifications
    |--------------------------------------------------------------------------
    */

    'jquery_not_loaded' => "jQuery is not loaded. Please include jQuery before this script.",
    'data_load_error' => "Error loading data",
    'show_loader' => "Show loader before starting the request",
    'hide_loader' => "Hide loader when data is loaded",
    'evo_mens_beneficiaires_pays_title' => "Monthly Evolution of Beneficiaries by Country",
    'nombre' => "Number",
    'ajax_load_error_console' => "An error occurred while loading data",
    'ajax_load_error_alert' => "An error occurred while loading data. Please try again later.",

    /*
    |--------------------------------------------------------------------------
    | Logs and Titles
    |--------------------------------------------------------------------------
    */

    'script_loaded_log' => "Monthly evolution headcount table script loaded",
    'loading_data_log' => "Loading monthly evolution headcounts by country",
    'response_received_log' => "Response received for headcounts by country:",
    'evo_mens_beneficiaires_table_title' => "Monthly Evolution of Beneficiaries by Country",
    'ajax_data_load_error_console' => "Error loading data for monthly evolution headcounts by country table",

    /*
    |--------------------------------------------------------------------------
    | Other Keys
    |--------------------------------------------------------------------------
    */

    'olea_statistics' => 'Olea Statistics',
    'olea_statistics_population' => "OLEA Statistics - Population",
    'liste_generale_effectifs' => "General Headcount List",
    'nom' => "Name",
    'prenoms' => "First Names",
    'sexe' => "Gender",
    'date_naissance' => "Date of Birth",
    'lieu_naissance' => "Place of Birth",
    'type_beneficiaire' => "Beneficiary Type",
    'college' => "College",
    'statut' => "Status",
    'date_entree' => "Entry Date",
    'date_sortie' => "Exit Date",
    'numero_carte' => "Card No.",
    'age' => "Age",
    'effectif_trate' => "Processed Headcount",
    'are_you_sure' => "Are you sure?",
    'delete_confirmation_message' => "Do you really want to delete these records? This action cannot be undone.",
    'cancel' => "Cancel",
    'delete' => "delete",
    'sinistres_list' => 'List of Claims',
    'prestataire' => 'Provider',
    'affection' => 'Condition',
    'acte' => 'Act',
    'part_olea' => 'Olea Share',
    'date_survenance_column_header' => 'Occurrence Date',

    /*
    |--------------------------------------------------------------------------
    | Age Statistics
    |--------------------------------------------------------------------------
    */

    'age_moyen_type_beneficiaire_title' => "Average Age by Beneficiary Type Year N",
    'age_moyen_type_beneficiaire_n1_title' => "Average Age by Beneficiary Type Year N-1",
    'moyenne_age' => "Average Age",
    'age_moyen' => "Average Age",
    'data_load_failure_message' => "An error occurred while loading data.",

    /*
    |--------------------------------------------------------------------------
    | Beneficiary Distribution
    |--------------------------------------------------------------------------
    */

    'repartition_beneficiaires_statut_title' => "Distribution of Beneficiaries by Status",
    'taux' => "Rate",

    /*
    |--------------------------------------------------------------------------
    | Data Loading
    |--------------------------------------------------------------------------
    */

    'loader_before_request' => "Show loader before starting the request",
    'loader_after_data_load' => "Hide loader when data is loaded",
    'repartition_beneficiaires_statut_comment' => "Distribution of beneficiaries by status",
    'repartition_beneficiaires_comment' => "Distribution of headcounts by beneficiaries.",
    'nombre_beneficiaires' => "Number of beneficiaries",

    /*
    |--------------------------------------------------------------------------
    | Charts
    |--------------------------------------------------------------------------
    */

    'chart_title' => 'Amounts Reimbursed by Beneficiary Type and by College',
    'y_axis_title' => 'Number of Members',
    'currency_suffix' => '',

    'new_chart_title' => 'Consumption by Type of Acts',
    'new_y_axis_title' => 'Cost',

    /*
    |--------------------------------------------------------------------------
    | New Expressions Added
    |--------------------------------------------------------------------------
    */

    'script_base_contractuelle_charge_confirmation' => 'Contractual base script loaded',
    'chargement_base_contractuelle_message' => 'Loading contractual base',
    'reponse_recue_message' => 'Response received:',
    'base_contractuelle' => 'Contractual Base - Policies with Insurer',
    'base_contractuelle_autofinancement' => 'Contractual Base - Self-Financing Policies', 
    'pays' => 'Country',
    'gestionnaire' => 'Manager',
    'assureur' => 'Insurer',
    'assure' => 'Insured',
    'numero_police' => 'Policy Number',
    'date_debut' => 'Start Date',
    'date_fin' => 'End Date',
    'prime_ht' => 'Premium Excluding Tax',
    'prime_ttc' => 'Premium Including Tax',
    'paye' => 'Paid',
    'impaye' => 'Unpaid',
    'data_loading_error_message' => 'Error loading contractual base data',
    'budget_ht' => 'Budget Excluding Tax',
    'budget_ttc' => 'Budget Including Tax',

    'carte_afrique' => 'Map of Africa',
    'pays_africains' => 'African Countries',

    // New expressions to add
    'periode_message' => 'Period:',
    'donnees_reçues_message' => 'Data received',
    'reponse_donnees_attendues_error' => 'The response does not contain the expected data',
    'labels_complets_message' => 'Complete labels',
    'donnees_completes_message' => 'Completed data',
    'depense_sante_titre_graphique' => 'Health Expenses Incurred - Insurer part',
    'montant' => 'Amount',
    'depense_serie_nom' => 'Expense',

    // New expressions to add
    'erreur_chargement_donnees_message' => 'An error occurred while loading data', // Error message in console
    'alerte_erreur_chargement' => 'An error occurred while loading data. Please try again later', // Alert message to user in case of error
    'comparaison_consommation_remboursements_titre' => 'Comparison of Consumption and Reimbursements by Country', // Chart title
    'montant_sinistre' => 'Claim Amount', // Data series name for claim amounts
    'montant_regle' => 'Settled Amount', // Data series name for settled amounts

    // New translations to add
    'evolution_mensuelle_titre_N' => 'Monthly Evolution of Consumptions Year N', // Table header title
    'evolution_mensuelle_titre' => 'Monthly Evolution of Consumptions Year N-1',
    'frais_reels_titre'=> 'Actual Expenses', // Title of the second column of the table
    'rembourse_titre'=> 'Reimbursed', // Title of the third column of the table
    'non_rembourse_titre'=> 'Not Reimbursed', // Title of the fourth column of the table (group header)
    'quote_part_titre'=> 'Share', // Title of the first sub-column in the "Not Reimbursed" group
    'exclusions_depassements_titre'=> 'Exclusions/Overruns', // Title of the second sub-column in the "Not Reimbursed" group

    // New translations to add
    'ratio_sinistres_primes_titre' => 'Claims/Premiums Ratio (C/P)', // Main title of the first table
    'annee_n_1_titre' => 'Year N-1', // Title of the column for the previous year
    'annee_n_titre' => 'Year N', // Title of the column for the current year
    'sinistres_rembourses_description' => 'Claims reimbursed to date', // Description in the first row of the table
    'primes_hors_taxes_description' => 'Premiums excluding tax', // Description in the second row of the table
    'primes_hors_taxes_charge_assureur_description' => 'Premiums Deducted from Insurer’s Liability',
    'ratio_sp_prorata_description' => 'Pro-rata C/P Ratio', // Description in the third row of the table
    'ratio_sp_projete_description' => 'Projected C/P Ratio', // Description in the fourth row of the table
    'chargement_texte' => 'Loading...', // Alternative text for the loader image
    'ratio_sinistres_primes_par_pays_titre' => 'Claims/Premiums Ratio (C/P) by Country', // Main title of the second table
    // 'pays_titre' => 'Country', // Title of the first column of the second table
    'total_prime_titre' => 'Total Premium', // Title of the second column of the second table
    'total_sinistre_titre' => 'Total Claim', // Title of the third column of the second table
    'sp_titre' => 'C/P', // Title of the fourth column of the second table
    'erreur_chargement_donnees_message' => 'Error loading data:', // Error message...


    'home' => 'Dashboard',
    'ratio_sp' => 'C/P Ratio',
    'consommation' => 'Consumption',
    'population' => 'Population',

    'documentation' => 'Documentation',
    'edition_excel' => 'Excel Edition',
    'edition_rapport' => 'Report Edition',


    'profile' => 'Profile',
    'reset_password' => 'Reset Password',
    'logout' => 'Logout',

    'stat_profile' => 'Profile Status',
    'filterPaysModal' => 'Country Filter Modal',
    'filtre_recherche' => 'Search Filter',
    'annuler_filtre_recherche' => 'Clear Filter',

    'select_filiale' => 'Select an Insured',
    'police' => 'Policy',
    'select_police' => 'Select a policy',
    'periode' => 'Period',
    'select_periode' => 'Select a period',
    'apply_filter' => 'Apply Filter',
    'selectFilialeModal' => 'Select Insured Modal',
    'updateFilialeModal' => 'Update Insured Modal',
    'clientListes' => 'Client List',
    'policesFormatted' => 'Formatted Policies',
    'periods_reference' => 'Reference Periods',
    'all_policies' => 'ALL POLICIES',

    'color_scheme' => 'Color Scheme',

    'dark_mode' => 'Dark Mode',
    'back_to_map' => 'Back to Map',

    'pages' => "Pages",
    'mail_settings' => "Mail Settings",
    'users_permissions' => "Users & Permissions",
    'transactions_report' => "Transactions Report",
    'users' => "Users",
    'products' => "Products",
    'notifications' => "Notifications",
    'filter_by' => "Filter by",
    'modal_selection_subsidiary' => "Modal Selection Subsidiary",

    
    'report_statistique_detaillé' => 'Detailed Statistical Report',
    'report_statistique_generale' => 'General Statistical Report',
    'download' => 'Download',

    'query_to_execute' => 'Query to execute',
    'select_a_query' => 'Select a query',
    'start_date' => 'Start date',
    'end_date' => 'End date',
    'loading' => 'Loading...',
    'execute_the_query' => 'Execute the query',

    'report_synthese_annuelle' => 'Annual Summary Report',


];


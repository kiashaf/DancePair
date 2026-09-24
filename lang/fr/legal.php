<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Politique d’annulation et de remboursement
    |--------------------------------------------------------------------------
    */

    'cancellation_refund' => [

        'title' => 'Politique d’annulation et de remboursement',

        'intro' => 'L’admissibilité à un remboursement dépend du type de cours réservé.',

        'online' => 'Cours en ligne : l’élève peut annuler jusqu’à 2 heures avant l’heure prévue du cours et recevoir un remboursement de 100 %.',

        'face_to_face' => 'Cours en personne : l’élève peut annuler jusqu’à 6 heures avant l’heure prévue du cours et recevoir un remboursement de 100 %.',

        'public_place' => 'Cours dans un lieu public : l’élève peut annuler jusqu’à 24 heures avant l’heure prévue du cours et recevoir un remboursement de 100 %.',

        'late_cancellation' => 'Toute annulation effectuée après le délai applicable n’est pas admissible à un remboursement automatique.',

        'teacher_cancellation' => 'Si le instructeur annule le cours, l’élève reçoit un remboursement de 100 %, peu importe le moment de l’annulation.',

        'admin_override' => 'DancePair peut exceptionnellement autoriser un remboursement en dehors de ces règles.',

        'original_payment_method' => 'Tout remboursement approuvé est retourné au mode de paiement utilisé lors de l’achat.',

        'full_refund' => 'Un remboursement complet correspond à 100 % du montant payé par l’élève pour cette réservation.',

    ],

];
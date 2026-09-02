<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentAvailabilityChangedNotification extends Notification
{
    use Queueable;


    public function __construct(
        public string $teacherName,
        public int $teacherId,
        public string $action,
        public array $oldDetails,
        public ?array $newDetails = null
    ) {
    }


    public function via(
        object $notifiable
    ): array {

        return [
            'database',
            'mail',
        ];
    }


    public function toMail(
        object $notifiable
    ): MailMessage {

        $locale =
            $this->resolveLocale(
                $notifiable
            );


        if (
            $this->action === 'deleted'
        ) {

            return $this->deletedMail(
                $notifiable,
                $locale
            );
        }


        if (
            $this->action === 'rate_changed'
        ) {

            return $this->rateChangedMail(
                $notifiable,
                $locale
            );
        }


        return $this->updatedMail(
            $notifiable,
            $locale
        );
    }


    public function toArray(
        object $notifiable
    ): array {

        $url =
            route(
                'student.teachers.show',
                $this->teacherId
            );


        if (
            $this->action === 'deleted'
        ) {

            return [

                'type' =>
                    'availability_deleted',

                'title' =>
                    'Lesson availability removed',

                'message' =>
                    $this->teacherName
                    . ' removed a lesson time you requested. '
                    . 'Your unpaid request was cancelled. Please choose another availability.',

                'title_en' =>
                    'Lesson availability removed',

                'message_en' =>
                    $this->teacherName
                    . ' removed a lesson time you requested. '
                    . 'Your unpaid request was cancelled. Please choose another availability.',

                'title_fr' =>
                    'Disponibilité de cours supprimée',

                'message_fr' =>
                    $this->teacherName
                    . ' a supprimé une plage horaire que vous aviez demandée. '
                    . 'Votre demande non payée a été annulée. Veuillez choisir une autre disponibilité.',

                'url' =>
                    $url,
            ];
        }


        if (
            $this->action === 'rate_changed'
        ) {

            return [

                'type' =>
                    'availability_rate_changed',

                'title' =>
                    'Lesson price changed',

                'message' =>
                    $this->teacherName
                    . ' changed the rate for a lesson you requested. '
                    . 'Your unpaid request was cancelled. Please review the new price and request again.',

                'title_en' =>
                    'Lesson price changed',

                'message_en' =>
                    $this->teacherName
                    . ' changed the rate for a lesson you requested. '
                    . 'Your unpaid request was cancelled. Please review the new price and request again.',

                'title_fr' =>
                    'Prix du cours modifié',

                'message_fr' =>
                    $this->teacherName
                    . ' a modifié le tarif d’un cours que vous aviez demandé. '
                    . 'Votre demande non payée a été annulée. Veuillez vérifier le nouveau prix et refaire une demande.',

                'url' =>
                    $url,
            ];
        }


        return [

            'type' =>
                'availability_changed',

            'title' =>
                'Lesson availability changed',

            'message' =>
                $this->teacherName
                . ' changed a lesson time you requested. '
                . 'Your unpaid request was cancelled. Please review the new details and request again.',

            'title_en' =>
                'Lesson availability changed',

            'message_en' =>
                $this->teacherName
                . ' changed a lesson time you requested. '
                . 'Your unpaid request was cancelled. Please review the new details and request again.',

            'title_fr' =>
                'Disponibilité du cours modifiée',

            'message_fr' =>
                $this->teacherName
                . ' a modifié un cours que vous aviez demandé. '
                . 'Votre demande non payée a été annulée. Veuillez vérifier les nouvelles informations et refaire une demande.',

            'url' =>
                $url,
        ];
    }


    private function updatedMail(
        object $notifiable,
        string $locale
    ): MailMessage {

        if ($locale === 'fr') {

            return (new MailMessage)

                ->subject(
                    'DancePair - Disponibilité de cours modifiée'
                )

                ->greeting(
                    'Bonjour '
                    . ($notifiable->name ?? '')
                    . ','
                )

                ->line(
                    $this->teacherName
                    . ' a modifié un cours que vous aviez demandé.'
                )

                ->line(
                    'Comme le cours n’avait pas encore été payé, votre ancienne demande a été annulée.'
                )

                ->line(
                    'Ancien cours : '
                    . $this->formatDetails(
                        $this->oldDetails,
                        'fr'
                    )
                )

                ->line(
                    'Nouveau cours : '
                    . $this->formatDetails(
                        $this->newDetails ?? [],
                        'fr'
                    )
                )

                ->action(
                    'Voir les nouvelles disponibilités',
                    route(
                        'student.teachers.show',
                        $this->teacherId
                    )
                )

                ->line(
                    'Si ce nouveau cours vous convient, vous devez envoyer une nouvelle demande.'
                );
        }


        return (new MailMessage)

            ->subject(
                'DancePair - Lesson availability changed'
            )

            ->greeting(
                'Hello '
                . ($notifiable->name ?? '')
                . ','
            )

            ->line(
                $this->teacherName
                . ' changed a lesson you had requested.'
            )

            ->line(
                'Because the lesson had not been paid yet, your previous request was cancelled.'
            )

            ->line(
                'Previous lesson: '
                . $this->formatDetails(
                    $this->oldDetails,
                    'en'
                )
            )

            ->line(
                'New lesson: '
                . $this->formatDetails(
                    $this->newDetails ?? [],
                    'en'
                )
            )

            ->action(
                'View New Availability',
                route(
                    'student.teachers.show',
                    $this->teacherId
                )
            )

            ->line(
                'If you still want this lesson, please send a new request.'
            );
    }


    private function deletedMail(
        object $notifiable,
        string $locale
    ): MailMessage {

        if ($locale === 'fr') {

            return (new MailMessage)

                ->subject(
                    'DancePair - Disponibilité de cours supprimée'
                )

                ->greeting(
                    'Bonjour '
                    . ($notifiable->name ?? '')
                    . ','
                )

                ->line(
                    $this->teacherName
                    . ' a supprimé une plage horaire que vous aviez demandée.'
                )

                ->line(
                    'Comme le cours n’avait pas encore été payé, votre demande a été annulée.'
                )

                ->line(
                    'Cours supprimé : '
                    . $this->formatDetails(
                        $this->oldDetails,
                        'fr'
                    )
                )

                ->action(
                    'Voir les disponibilités',
                    route(
                        'student.teachers.show',
                        $this->teacherId
                    )
                )

                ->line(
                    'Aucun paiement n’a été prélevé. Vous pouvez choisir une autre disponibilité et envoyer une nouvelle demande.'
                );
        }


        return (new MailMessage)

            ->subject(
                'DancePair - Lesson availability removed'
            )

            ->greeting(
                'Hello '
                . ($notifiable->name ?? '')
                . ','
            )

            ->line(
                $this->teacherName
                . ' removed a lesson time you had requested.'
            )

            ->line(
                'Because the lesson had not been paid yet, your request was cancelled.'
            )

            ->line(
                'Removed lesson: '
                . $this->formatDetails(
                    $this->oldDetails,
                    'en'
                )
            )

            ->action(
                'View Availability',
                route(
                    'student.teachers.show',
                    $this->teacherId
                )
            )

            ->line(
                'No payment was taken. You can choose another availability and send a new request.'
            );
    }


    private function rateChangedMail(
        object $notifiable,
        string $locale
    ): MailMessage {

        if ($locale === 'fr') {

            return (new MailMessage)

                ->subject(
                    'DancePair - Prix du cours modifié'
                )

                ->greeting(
                    'Bonjour '
                    . ($notifiable->name ?? '')
                    . ','
                )

                ->line(
                    $this->teacherName
                    . ' a modifié le tarif d’un cours que vous aviez demandé.'
                )

                ->line(
                    'Comme le cours n’avait pas encore été payé, votre ancienne demande a été annulée.'
                )

                ->line(
                    'Ancien cours : '
                    . $this->formatDetails(
                        $this->oldDetails,
                        'fr'
                    )
                )

                ->line(
                    'Nouveau prix : '
                    . $this->formatDetails(
                        $this->newDetails ?? [],
                        'fr'
                    )
                )

                ->action(
                    'Voir le nouveau prix',
                    route(
                        'student.teachers.show',
                        $this->teacherId
                    )
                )

                ->line(
                    'Si vous souhaitez toujours ce cours, veuillez envoyer une nouvelle demande.'
                );
        }


        return (new MailMessage)

            ->subject(
                'DancePair - Lesson price changed'
            )

            ->greeting(
                'Hello '
                . ($notifiable->name ?? '')
                . ','
            )

            ->line(
                $this->teacherName
                . ' changed the rate for a lesson you had requested.'
            )

            ->line(
                'Because the lesson had not been paid yet, your previous request was cancelled.'
            )

            ->line(
                'Previous lesson: '
                . $this->formatDetails(
                    $this->oldDetails,
                    'en'
                )
            )

            ->line(
                'New price: '
                . $this->formatDetails(
                    $this->newDetails ?? [],
                    'en'
                )
            )

            ->action(
                'View New Price',
                route(
                    'student.teachers.show',
                    $this->teacherId
                )
            )

            ->line(
                'If you still want this lesson, please send a new request.'
            );
    }


    private function resolveLocale(
        object $notifiable
    ): string {

        $locale =
            $notifiable->locale
            ??
            $notifiable->language
            ??
            'en';


        return in_array(
            $locale,
            [
                'en',
                'fr',
            ],
            true
        )
            ? $locale
            : 'en';
    }


    private function formatDetails(
        array $details,
        string $locale
    ): string {

        if (!$details) {
            return '';
        }


        $dance =
            $details['dance_style']
            ?? 'Dance';


        $date =
            $details['date']
            ?? '';


        $start =
            $details['start_time']
            ?? '';


        $end =
            $details['end_time']
            ?? '';


        $price =
            array_key_exists(
                'price',
                $details
            )
            &&
            $details['price'] !== null

                ? '$'
                    . number_format(
                        (float) $details['price'],
                        2
                    )

                : null;


        $rate =
            array_key_exists(
                'hourly_rate',
                $details
            )
            &&
            $details['hourly_rate'] !== null

                ? '$'
                    . number_format(
                        (float) $details['hourly_rate'],
                        2
                    )
                    . '/h'

                : null;


        $text =
            trim(
                $dance
                . ' - '
                . $date
                . ' - '
                . $start
                . ' - '
                . $end
            );


        if ($rate) {

            $text .=
                $locale === 'fr'
                    ? ' - Tarif ' . $rate
                    : ' - Rate ' . $rate;
        }


        if ($price) {

            $text .=
                ' - Total '
                . $price;
        }


        return $text;
    }
}
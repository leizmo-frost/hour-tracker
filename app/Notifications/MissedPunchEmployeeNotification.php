<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Employee;

class MissedPunchEmployeeNotification extends Notification
{
    use Queueable; // Crucial: Don't block the UI

    public function via($notifiable): array { return ['database', 'mail']; }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Don\'t forget to clock out!')
            ->line('We noticed you haven\'t clocked out yet today.')
            ->action('Clock Out Now', url('/clock'));
    }

    public function toArray($notifiable): array {
        return ['message' => 'Missed clock-out reminder.'];
    }

    public function __construct(public Employee $employee, public int $missedCount) {}

    public function via($notifiable): array { return ['database']; } // Only DB to avoid spamming manager emails

    public function toArray($notifiable): array {
        return [
            'message' => "{$this->employee->user->name} has missed {$this->missedCount} punches this week.",
            'employee_id' => $this->employee->id,
        ];
    }

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
}

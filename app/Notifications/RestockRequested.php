<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\StockAlert;

class RestockRequested extends Notification
{
    use Queueable;

    protected $alert;

    public function __construct(StockAlert $alert)
    {
        $this->alert = $alert;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Solicitação de Reposição de Estoque - ' . $this->alert->medicine->name)
                    ->line('Nova solicitação de reposição foi criada:')
                    ->line('Medicamento: ' . $this->alert->medicine->name)
                    ->line('Quantidade solicitada: ' . $this->alert->quantity)
                    ->line('Prioridade: ' . StockAlert::getPriorities()[$this->alert->priority])
                    ->action('Ver Solicitação', url('/stock-alerts'))
                    ->line('Obrigado por usar nosso sistema!');
    }

    public function toArray($notifiable)
    {
        return [
            'alert_id' => $this->alert->id,
            'medicine_id' => $this->alert->medicine_id,
            'medicine_name' => $this->alert->medicine->name,
            'quantity' => $this->alert->quantity,
            'priority' => $this->alert->priority,
            'message' => 'Nova solicitação de reposição para ' . $this->alert->medicine->name,
            'url' => '/stock-alerts'
        ];
    }
}

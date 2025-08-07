<?php

namespace App\Notifications;

use App\Models\StockAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockAlertRejected extends Notification
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
                    ->subject('Pedido de Reposição Rejeitado - #' . $this->alert->id)
                    ->line('Seu pedido de reposição foi rejeitado:')
                    ->line('Medicamento: ' . $this->alert->medicine->name)
                    ->line('Quantidade solicitada: ' . $this->alert->quantity)
                    ->line('Motivo: ' . $this->alert->rejection_reason)
                    ->action('Ver Pedido', url('/stock-alerts/' . $this->alert->id))
                    ->line('Por favor, revise os detalhes e entre em contato se necessário.');
    }

    public function toArray($notifiable)
    {
        return [
            'alert_id' => $this->alert->id,
            'message' => 'Seu pedido de reposição #' . $this->alert->id . ' foi rejeitado',
            'url' => '/stock-alerts/' . $this->alert->id
        ];
    }
}

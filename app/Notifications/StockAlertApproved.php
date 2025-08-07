<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\StockAlert;
class StockAlertApproved extends Notification
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
                    ->subject('Pedido de Reposição Aprovado - #' . $this->alert->id)
                    ->line('Seu pedido de reposição foi aprovado:')
                    ->line('Medicamento: ' . $this->alert->medicine->name)
                    ->line('Quantidade solicitada: ' . $this->alert->quantity)
                    ->line('Quantidade aprovada: ' . $this->alert->approved_quantity)
                    ->action('Ver Pedido', url('/stock-alerts/' . $this->alert->id))
                    ->line('Obrigado por usar nosso sistema!');
    }

    public function toArray($notifiable)
    {
        return [
            'alert_id' => $this->alert->id,
            'message' => 'Seu pedido de reposição #' . $this->alert->id . ' foi aprovado',
            'url' => '/stock-alerts/' . $this->alert->id
        ];
    }
}

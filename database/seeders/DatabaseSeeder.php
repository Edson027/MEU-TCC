<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

         $this->call([
            UserSeeder::class,
          MedicineSeeder::class,
        ]);
/*
    <!-- Notificações 
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount = auth()->user()->unreadNotifications->count())
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadCount }}
                                    <span class="visually-hidden">Notificações não lidas</span>
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown">
                            <li>
                                <h6 class="dropdown-header">
                                    Notificações
                                    <a href="{{ route('notifications.index') }}" class="float-end small">Ver todas</a>
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                            <li>
                                <a class="dropdown-item notification-item {{ $notification->unread() ? 'unread' : '' }}" 
                                   href="{{ route('notifications.markAsRead', $notification->id) }}">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="notification-dot {{ $notification->data['urgency'] ?? 'medium' }}"></span>
                                            <strong>{{ $notification->data['title'] ?? 'Alerta de Estoque' }}</strong>
                                        </div>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 text-muted small">{{ Str::limit($notification->data['message'] ?? '', 50) }}</p>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider m-0"></li>
                            @empty
                            <li>
                                <a class="dropdown-item text-center py-3 text-muted" href="#">
                                    Nenhuma notificação recente
                                </a>
                            </li>
                            @endforelse
                            
                            <li>
                                <a class="dropdown-item text-center py-2 bg-light" href="{{ route('notifications.index') }}">
                                    <strong>Ver todas as notificações</strong>
                                </a>
                            </li>
                        </ul>
                    </li>
                    -->
         */
    }
}

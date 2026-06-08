<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
{
    $url = url(route('password.reset', [
        'token' => $token,
        'email' => $this->email,
    ], false));

    $this->notify(new class($url) extends ResetPassword {
        public function __construct(public $url) {}

        public function toMail($notifiable)
        {
            return (new MailMessage)
                ->subject('Reset Password Admin Temuin.id')
                ->greeting('Halo Admin Temuin.id!')
                ->line('Kami menerima permintaan untuk mengganti password akun admin Temuin.id.')
                ->action('Reset Password', $this->url)
                ->line('Link reset password ini berlaku selama 60 menit.')
                ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.');
        }
    });
}
}

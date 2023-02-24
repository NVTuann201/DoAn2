<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class RegisterUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public $user;
    public $checkNewAccount;
    
    public function __construct(User $user, $checkNewAccount = false)
    {
        $this->user = $user;
        $this->checkNewAccount = $checkNewAccount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('example@example.com')
                    ->subject($this->checkNewAccount ? '[NBDS] Xác nhận tài khoản':'[NBDS] Xác nhận thay đổi tài khoản')
                    ->markdown('emails.register.user')
                    ->with([
                        'user' => $this->user,
                        'name' => $this->user->name,
                        'actionUrl' => route('confirm.user', $this->user->email_token). '?email=' . base64_encode($this->user->email),
                        'actionButton' => 'Xác nhận',
                        'actionText1' => $this->checkNewAccount ? 'Bạn đã đăng kí thành viên tại hệ thống NBDS, xin vui lòng nhấn vào nút' : 
                                                                  'Bạn nhận được email này do đã yêu cầu thay đổi thông tin tài khoản tại hệ thống NBDS, xin vui lòng nhấn vào nút',
                        'actionText2' => $this->checkNewAccount ? 'bên dưới để kích hoạt tài khoản' : 'bên dưới để xác nhận thay đổi trên'
                    ]); 
    }
}

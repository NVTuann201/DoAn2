<?php

namespace App\Observers;

use App\User;
use App\Traits\GetDataCache;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterUser;
use Illuminate\Http\Request;

class UserObserver
{
    use GetDataCache;

    public $request;
    public function __construct(Request $request)
    {
        $this->request = $request->input('media.id');
    }

    public function created(User $model)
    {
        if (!config('app.debug'))
            Mail::to($model->email)->queue(new RegisterUser($model, true));
    }

    public function updated(User $model)
    {
        // if (!config('app.debug'))
        //     Mail::to($model->email)->queue(new RegisterUser($model));
        $this->updateAttachMedia($model, $this->request, 'App\User');
    }

    public function deleted(User $model)
    {
        $this->deleteAttachMedia($model);
    }
}

<?php

namespace App\Observers;

use App\Organization;
use App\Traits\GetDataCache;
use Illuminate\Http\Request;

class OrganizationObserver
{
    use GetDataCache;

    public $request;
    public function __construct(Request $request)
    {
        $this->request = $request->input('media.id');
    }

    public function created(Organization $model)
    {
        $this->addAttachMedia($model, $this->request);
    }

    public function updated(Organization $model)
    {
        $this->updateAttachMedia($model, $this->request, 'App\Organization');
    }

    public function deleted(Organization $model)
    {
        $this->deleteAttachMedia($model);
    }
}

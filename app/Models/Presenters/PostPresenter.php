<?php

namespace App\Models\Presenters;

use App\Str;
use App\Tree;
use Spatie\Url\Url;
use Illuminate\Support\Carbon;

class PostPresenter extends BasePresenter
{
    public function image() : string
    {
        if (! Str::startsWith($this->model->image, 'https://res.cloudinary.com')) {
            return $this->model->image;
        }

        if (str_contains($this->model->image, '/dpr_auto,f_auto,q_auto,w_auto')) {
            return $this->model->image;
        }

        return str_replace('/upload', '/upload/dpr_auto,f_auto,q_auto,w_auto', $this->model->image);
    }

    public function tree() : array
    {
        return (new Tree)->build($this->content());
    }

    public function content() : string
    {
        return $this->renderAsMarkdown('content', $this->model->content);
    }

    public function teaser() : string
    {
        return $this->renderAsMarkdown('teaser', $this->model->teaser);
    }

    public function communityLinkDomain() : string
    {
        return str_replace('www.', '', Url::fromString($this->model->community_link)->getHost());
    }

    public function lastUpdated() : string
    {
        // I have no idea why sometimes, manually_updated_at is a string
        // instead of being casted to a Carbon instance by Eloquent.
        if (is_string($this->model->manually_updated_at)) {
            $manuallyUpdatedAt = Carbon::parse($this->model->manually_updated_at);
        }

        return ($manuallyUpdatedAt ?? $this->model->created_at)->isoFormat('LL');
    }
}

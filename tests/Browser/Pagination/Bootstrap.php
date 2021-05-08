<?php

namespace Tests\Browser\Pagination;

use Tests\Browser\Pagination\Post;
use Illuminate\Support\Facades\View;
use Actcmscss\Component as BaseComponent;
use Actcmscss\WithPagination;

class Bootstrap extends BaseComponent
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return View::file(__DIR__.'/view.blade.php', [
            'posts' => Post::paginate(3),
        ]);
    }
}

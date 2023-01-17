<?php

namespace App\Console\Commands;

use App\Models\Post;
use Spatie\Sitemap\Sitemap;
use Illuminate\Console\Command;

class SitemapGenerateCommand extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        $sitemap->add(route('home'));

        $sitemap->add(route('consulting.laravel'));

        Post::latest()->get()->each(fn ($p) => $sitemap->add(route('posts.show', $p)));

        $sitemap->writeToFile(public_path('/sitemap.xml'));

        $this->info('Sitemap successfully generated.');

        return Command::SUCCESS;
    }
}

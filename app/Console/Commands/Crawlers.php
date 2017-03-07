<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Interfaces\CrawlersInterface;
class Crawlers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler:cloudPlayList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawler the cloudmusic playlist';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CrawlersInterface $crawlers)
    {   
        parent::__construct();
        $this->crawler = $crawlers;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->confirm('确定要开始抓取吗')){
            $this->crawler->getPlayList();
        }
    }
}

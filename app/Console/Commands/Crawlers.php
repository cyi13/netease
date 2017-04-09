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
    protected $signature = 'crawler:cloud {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crawler the cloudmusic';

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
//        if($this->confirm('do you want to start collect now ?')){

            $collectType = $this->argument('type');
            switch ($collectType) {
                case 'playlist':
                    //抓取歌单
                    $this->crawler->getPlayList();
                    break;
                case 'musiclist':
                    $this->crawler->collectMusicMessage();
                    break;
                case 'put':
                    $this->crawler->putPlayListIntoMysqlFromRedis();
                    break;
                case 'proxyCheck':
                    $this->crawler->checkProxy();
                    break;
            }
//        }
    }
}

<?php

namespace App\Console\Commands;

use App\CarMark;
use App\CarModel;
use App\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPHtmlParser\Dom;

class Categories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * 'all' load all marks and model
     * 'updates' load only update
     * @var string
     */
    protected $signature = "turbo:categories";
//    protected $signature = "turbo:categories {action}";
    /**
     * The console command description.
     *
     * @var string
     */
        protected $description = "Load all cars marks and models";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        /**
         *  for getting all categories we can get  home page
         */
        $page = env('TURBO_MAIN_PAGE' , 'https://turbo.az/');
            $html = file_get_contents($page);
            $dom = new Dom;
            $dom->load($html);

            $marks = $dom->find('select#q_make option ');

            foreach ($marks as $mark){
                if ($mark->text == "Bütün markalar") continue;
                $id = $mark->getAttribute('value');

                /**
                 * all categories in one page
                 */
                DB::table('car_marks')->insertOrIgnore([
                    'id' => (int)$id ,
                    'name' => $mark->text
                ]);
                $models = $dom->find(".{$id}");
                foreach ($models as $model){
                    $modelId = (int)$model->getAttribute('value');
                    if ($modelId == "0") continue;
                    DB::table('car_models')->insertOrIgnore([
                        'id' => $modelId,
                        'name' => $model->text,
                        'car_mark_id' => (int)$id
                    ]);

                }
        }
        $this->alert("successfully get/update marks and models");
    }


    /**
     * @return bool if need some force or else but we does not need that functionality
     */
    public function checkArgument()
    {
        if ($this->argument('action') === "force") {
            while (true) {
                $answer = mb_strtoupper($this->ask('all categories was removed are you sure? y/n'));
                if ($answer == "Y") return true;
                if ($answer == "N") {
                        return false;
                }

            }
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Car;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use PHPHtmlParser\Dom;

class Cars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * date format dd.mm.yyyy
     * skip true or false
     * @var string
     */
    protected $signature = 'turbo:cars {--date=} {--skip=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all updates  in turbo.az/autos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('memory_limit' , '1024M');
        parent::__construct();
    }


    public function handle()
    {
        $link = env('TURBO_MAIN_PAGE', 'http://turbo.az/');
        $page = 1;

        /**
         * insert elediyimiz  maşinlarin id si
         */
        $ids = [];
        while (true) {
            $html = file_get_contents($link . "autos?page=" . $page++);
            $dom = new Dom();
            $dom->load($html);
            $products = $dom->find('.products');


            /**
             * product klasinin 3cu elementi esas olan elanlardi
             */
            $productsItems = $products[2]->find('.products-i');


            /**
             * ikinci dovrədəndə cixmasi ucun flag
             */
            $flag = false;
            foreach ($productsItems as $item) {
                $createdString = $item->find('.products-description .products-bottom');
                if ($this->isValidDate($createdString->text)) {
                    $carLink = $item->find('.products-i-link')->getAttribute('href');
                    $oneCar = file_get_contents($link . $carLink );
                    $domCar = new Dom();
                    $domCar->load($oneCar);

                    $properties = $domCar->find('.product-properties-i');
                    $modelLink = $properties[2]->find('.product-properties-value a')->getAttribute('href');


                    try {
                        $id = (int)$domCar->find('.terminal-promotion__steps_product-id')->text;

                        /**
                         * paginasiyada id tekrarlana biler dyə
                         */
                        if (in_array($id, $ids)) continue;
                        array_push($ids, $id);
                        Car::create([
                            'id' => $id,
                            'price' =>(int)str_replace(" " , "" , $properties[13]->find('.product-price')->text) ,
                            'currency' => $properties[13]->find('.product-price span')->text,
                            'city' => $properties[0]->find('.product-properties-value')->text,
                            'year' => $properties[3]->find('.product-properties-value a')->text,
                            'added_at' => Carbon::parse(explode(',', $createdString->text)[1])->toDateTimeString(),
                            'car_model_id' => (int)substr($modelLink, strripos($modelLink, "=") + 1)
                        ]);
                    } catch (QueryException $e) {
                        if ($e->errorInfo[1] == 1062) {
                            /**
                             * task error veribse onnan evvelki carlara accesiiz olsun dye
                             */
                            if ($this->option('skip')){
                                    continue;
                            }else{
                                $flag = true;
                                break;
                            }
                        }
                    }

                } else {
                    $flag = true;
                    break;
                }

            }
            if ($flag) break;

        }
        $this->alert("Successfully get all needs cars. inserted : " . count($ids));
    }


    public function isValidDate($text)
    {
        if (preg_match('/^.*\,\s([0-9\.]+)\s.*$/', $text, $match)){
            $from  = $this->option('date') ?? env('FROM_DATE', '01.03.2020');
            if (Carbon::parse($match[1]) >= Carbon::parse($from)) return true;
        }
        return false;
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Option;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE options;');                

        Option::create(array(                
            'name' => 'url_get_images_from_vietnam_natural_heritages',
            'value'    => 'http://nhm.howizbiz.com/api/contributions'      
        )); 

        Option::create(array(                
            'name' => 'url_news',
            'value'    => 'http://dstt.howizbiz.com/wp-json/wp/v2/posts?_embed&per_page=8&page=1&content=embed'      
        ));
    }
}

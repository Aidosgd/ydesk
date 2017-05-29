<?php

namespace App\Providers;

use App\Font;
use App\Models\SeoFields;
use Ibec\Media\Gallery;
use Ibec\Menu\Database\Menu;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Factory $view)
    {
        $menu = Menu::with('children')->find(1);

        $main_menu = $menu
            ->descendants()
            ->with('linkable')
            ->get()
            ->toHierarchy();

        $view->share('main_menu', $main_menu);

        $seo_fields = SeoFields::first();

        app()['seo_fields'] = $seo_fields;

        $seo = [
            'title' => $seo_fields->node->title,
            'description' => $seo_fields->node->description,
            'keywords' => $seo_fields->node->keywords
        ];

        $main_slide = Gallery::with('images')->find(1)->images()->first()->path;

        $view->share(compact('seo', 'font', 'main_slide'));



        $ckeditorBasic = [
            'language' => 'ru',
            'filebrowserImageBrowseUrl' => '/' . admin_prefix('media/manager/frame?multiple=0', '/'),
            'filebrowserBrowseUrl' => '/' . admin_prefix('media/manager/frame?multiple=0&mediaType=files', '/'),
            'skin' => 'moono',
        ];

        $adminFonts = Font::all('font_family')->toArray();

        $myFonts = [];
        foreach ($adminFonts as $font){
            $myFonts[] = $font['font_family'];
        }
//        $myFonts = ['Aclonica', 'Allan', 'Allerta', 'Allerta Stencil', 'Amaranth', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Anton', 'Architects Daughter', 'Arimo', 'Artifika', 'Arvo', 'Astloch', 'Bangers', 'Battambang', 'Bayon', 'Bentham', 'Bevan', 'Bigshot One', 'Bokor', 'Brawler', 'Buda', 'Cabin', 'Cabin Sketch', 'Calligraffitti', 'Candal', 'Cantarell', 'Cardo', 'Carter One', 'Caudex', 'Chenla', 'Cherry Cream Soda', 'Chewy', 'Coda', 'Coda Caption', 'Coming Soon', 'Content', 'Copse', 'Corben', 'Cousine', 'Covered By Your Grace', 'Crafty Girls', 'Crimson Text', 'Crushed', 'Cuprum', 'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Didact Gothic', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'EB Garamond', 'Expletus Sans', 'Fontdiner Swanky', 'Francois One', 'Freehand', 'GFS Didot', 'GFS Neohellenic', 'Geo', 'Goudy Bookletter 1911', 'Gruppo', 'Hanuman', 'Holtwood One SC', 'Homemade Apple', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC', 'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer', 'IM Fell Great Primer SC', 'Inconsolata', 'Indie Flower', 'Irish Grover', 'Josefin Sans', 'Josefin Slab', 'Judson', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kenia', 'Khmer', 'Koulen', 'Kranky', 'Kreon', 'Kristi', 'Lato', 'League Script', 'Lekton', 'Limelight', 'Lobster', 'Lora', 'Luckiest Guy', 'Maiden Orange', 'Mako', 'Maven Pro', 'Meddon', 'MedievalSharp', 'Megrim', 'Merriweather', 'Metal', 'Metrophobic', 'Michroma', 'Miltonian', 'Miltonian Tattoo', 'Molengo', 'Monofett', 'Moul', 'Moulpali', 'Mountains of Christmas', 'Muli', 'Neucha', 'Neuton', 'News Cycle', 'Nobile', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round', 'Nova Script', 'Nova Slim', 'Nova Square', 'Nunito', 'OFL Sorts Mill Goudy TT', 'Odor Mean Chey', 'Old Standard TT', 'Open Sans', 'Open Sans Condensed', 'Orbitron', 'Oswald', 'Over the Rainbow', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif', 'PT Serif Caption', 'Pacifico', 'Paytone One', 'Permanent Marker', 'Philosopher', 'Play', 'Playfair Display', 'Podkova', 'Preahvihear', 'Puritan', 'Quattrocento', 'Quattrocento Sans', 'Radley', 'Raleway', 'Reenie Beanie', 'Rock Salt', 'Rokkitt', 'Ruslan Display', 'Schoolbell', 'Shanti', 'Siemreap', 'Sigmar One', 'Six Caps', 'Slackey', 'Smythe', 'Sniglet', 'Special Elite', 'Sue Ellen Francisco', 'Sunshiney', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate', 'Tangerine', 'Taprom', 'Tenor Sans', 'Terminal Dosis Light', 'The Girl Next Door', 'Tinos', 'Ubuntu', 'Ultra', 'UnifrakturCook', 'UnifrakturMaguntia', 'Unkempt', 'VT323', 'Vibur', 'Vollkorn', 'Waiting for the Sunrise', 'Wallpoet', 'Walter Turncoat', 'Wire One', 'Yanone Kaffeesatz'];

//        dd($myFonts);


        $view->share('myFonts', $myFonts);

        $ckeditorBasic['font_names'] = 'serif;sans serif;monospace;cursive;fantasy';

        for($i = 0; $i<count($myFonts); $i++){
            $ckeditorBasic['font_names'] = $ckeditorBasic['font_names'] . ';' . $myFonts[$i];
            $myFonts[$i] = 'http://fonts.googleapis.com/css?family='
                . str_replace('+', ' ', $myFonts[$i]);
        }

        $ckeditorBasic['contentsCss'] = array_merge(['/js/ckeditor/contents.css'], $myFonts);

        $view->share('ckeditorBasic', $ckeditorBasic);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

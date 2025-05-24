<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConvertHtmlToPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @var string
     */
//{page-width : 200mm} {page-height : 200m}
    protected $signature = 'convert:html-to-pdf {html : public/input.html} {output : public/file.pdf} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert HTML to pdf using wkhtmltopdf';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $html = $this->argument('html');
        $output = $this->argument('output');
//        $width = $this->argument('page-width');
//        $height = $this->argument('page-height');

        exec("wkhtmltopdf --disable-smart-shrinking --page-width 148mm --page-height 234mm -T 0 -B 0 -L 0 -R 0 $html $output");
//        logger("wkhtmltopdf --disable-smart-shrinking --page-width 148mm --page-height 234mm -T 0 -B 0 -L 0 -R 0 $html $output");
        $this->info("HTML converted to image and saved to $output");
    }
}

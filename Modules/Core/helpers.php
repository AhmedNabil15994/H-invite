<?php

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Valuestore\Valuestore;


if (!function_exists('convertArabicToEnglish')) {
    function convertArabicToEnglish($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}

if (!function_exists('convertPixelToMillimeter')) {
    // 1mm == 3.7795
    function convertPixelToMillimeter($sizeInPixel) {
        return (round($sizeInPixel / 3.7795)).'mm ';
    }
}

if (!function_exists('get_pdf_dimensions')) {
    function get_pdf_dimensions($path) {
        $image = new Imagick($path);
        $geo=$image->getImageGeometry();
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $geo['width'] = round($geo['width'] * 4/3);
        $geo['height'] = round($geo['height'] * 4/3);
        if(!in_array($type,['pdf'])){
            return  $geo;
        }
        return $geo;
    }
}

if (!function_exists('convertPdfToJpeg')) {
    function convertPdfToJpeg($pdf,$party){
        $sizes = get_pdf_dimensions($pdf);
        $type = pathinfo($pdf, PATHINFO_EXTENSION);;
        if(!in_array($type,['pdf'])){
            $sizes['image'] = $party->getFirstMediaUrl('images');
            $sizes['path']  = $party->getFirstMediaPath('images');
            return  $sizes;
        }

        $image = new Imagick();
        $image->setResolution( 96, 96 );
        $image->readImage($pdf);
        $image->setImageFormat( "jpeg" );
        $image->setImageCompression(imagick::COMPRESSION_JPEG);
        $image->setCompressionQuality(100);

        $image->writeImage(public_path().'/uploads/parties/party-'.$party->id.'.jpeg');
        return [
            'image' => \URL::to('/uploads/parties/party-'.$party->id.'.jpeg'),
            'height' => $sizes['height'],
            'width' => $sizes['width'],
            'path'  => public_path().'/uploads/parties/party-'.$party->id.'.jpeg',
        ];
    }
}

if (!function_exists('validatePhone')) {
    function validatePhone($number){
        $number = convertArabicToEnglish($number);
        return preg_replace("/[^0-9]/", "", $number);
    }
}

if (!function_exists('generateQrCode')) {
    function generateQrCode($code){
        $filename_path = $code.".png";
        $decoded = base64_decode(base64_encode(QrCode::format('png')->size(setting('qr_width'))->generate( route('frontend.invitations.redeem',['code'=>$code]))));
        return file_put_contents(public_path('/uploads/qr/').$filename_path,$decoded);
    }
}

if (!function_exists('generateInvitation')) {
    function generateInvitation($invitation,$qr,$invitationObj,$default=1,$distX=null,$distY=null,$height=null,$width=null){
        $image_1 = imagecreatefromstring((file_get_contents($invitation)));
        $image_2 = imagecreatefrompng($qr);
        $invitationHeight = 880;
        $invitationWidth = 560;
        $qrHeight = setting('qr_height');
        $qrWidth  = setting('qr_width');
        $modelDimensions = $invitationObj?->party?->dimensions ? (array) json_decode($invitationObj?->party?->dimensions) : [];
        if($default){
            $qrHeight = imagesy($image_2);
            $qrWidth  = imagesx($image_2);

            $distX = $modelDimensions['dist_x'] ?? ((($invitationWidth - $qrWidth) / 2) - 60);
            $distY = $modelDimensions['dist_y'] ?? $invitationHeight - $qrHeight - 60;

            $distX2 = $modelDimensions['dist_x2'] ?? (($invitationWidth - $qrWidth) / 2) + 75;
            $distY2 = $modelDimensions['dist_y2'] ?? $invitationHeight - $qrHeight - 40;
        }

        if($invitationObj->invitation_number && $invitationObj->party->title){
            $invitationLink = route('frontend.parties.actions',['party_id'=> $invitationObj->party_id,'contact_id' => ($invitationObj->contact_id *34567)]);
//            $invitationLink = str_replace('http://127.0.0.1:8000','https://484d-154-182-175-99.ngrok-free.app',$invitationLink);
            $invitationNumberDesign = view('party::dashboard.parties.components.invitations_design_pdf',[
                'url'           =>  $invitationLink,
                'number'        => $invitationObj->invitation_number,
                'qr'            => URL::to('/uploads/qr/'.$invitationObj->code.'.png'),
                'distY'         => $distY,
                'distX'         => $distX,
                'distY2'         => $distY2,
                'distX2'         => $distX2,
                'image'         => $invitation,
                'height'        => $height,
                'width'         => $width,
                'qr_width'      => setting('qr_width'),
            ])->render();

            $path = public_path().'/uploads/invitations/';
            $path2 = public_path().'/uploads/parties/party-'.$invitationObj->party_id.'/';
            if(!file_exists($path2)){
                mkdir($path2);
            }
            $commandHeight = ' ' . convertPixelToMillimeter($invitationHeight);
            $commandWidth = ' ' . convertPixelToMillimeter($invitationWidth);
//.$commandWidth . $commandHeight
            file_put_contents($path.$invitationObj->code.'-'.$invitationObj->invitation_number.'.html',$invitationNumberDesign);
            \Illuminate\Support\Facades\Artisan::call('convert:html-to-image '.($path.$invitationObj->code.'-'.$invitationObj->invitation_number.'.html').' '.($path.$invitationObj->code.'-'.$invitationObj->invitation_number.'.png'));
            \Illuminate\Support\Facades\Artisan::call('convert:html-to-pdf ' .($path.$invitationObj->code.'-'.$invitationObj->invitation_number.'.html').' '.($path2.$invitationObj->invitation_number.'.pdf'));
            unlink($path.$invitationObj->code.'-'.$invitationObj->invitation_number.'.html');
            $image_3 = imagecreatefrompng($path.$invitationObj->code.'-'.$invitationObj->invitation_number.'.png');
        }

        imagealphablending($image_1, true);
        imagesavealpha($image_1, true);
        imagecopy($image_1, $image_2, $distX, $distY, 0, 0, $qrWidth, $qrHeight);
        imagepng($image_1, public_path().'/uploads/invitations/'.$invitationObj->code.'.png');

        if(file_exists(public_path().'/uploads/invitations/'.$invitationObj->code.'-'.$invitationObj->invitation_number.'.png')) {
            $numberDistY = $distY + $qrHeight + 5;
            $image_4 = imagecreatefrompng(public_path().'/uploads/invitations/'.$invitationObj->code.'.png');
            imagealphablending($image_4, true);
            imagesavealpha($image_4, true);
            imagecopy($image_4, $image_3, $distX, $numberDistY, 0, 0, $qrWidth, 25);
            imagepng($image_4, public_path().'/uploads/invitations/'.$invitationObj->code.'.png');
            unlink(public_path().'/uploads/invitations/'.$invitationObj->code.'-'.$invitationObj->invitation_number.'.png');
//            $image_3 = imagecreatefrompng($path.$number.'.png');
        }

        return \URL::to('/uploads/invitations/'.$invitationObj->code.'.png');
    }
}

if (!function_exists('base64')) {
    function base64($imgUrl)
    {
        $folder = 'uploads/api';
        $path   = $imgUrl;
        $type   = '.jpg';
        $imgname = md5(rand() * time()) . '.' . $type;
        // Get new name of image Url & Path of folder to save in it
        $fname = $folder . '/' . $imgname;
        $img = Image::make($path);
        // End of this proccess
        $img->save($fname);
        return $fname;
    }
}

if (!function_exists('calcDiscount')) {
    function calcDiscount($price,$orderCoupon)
    {
        $discount = 0;
        if($orderCoupon->discount_type == 'percentage'){
            $discount = round(floatval($price) * $orderCoupon->discount_percentage / 100,3);
        }else{
            $discount = $orderCoupon->discount_value;
        }
        return [
            'price_after_discount' => round(floatval($price) - $discount,3),
            'discount'  => $discount,
        ];
    }
}
// Get Setting Values

if (!function_exists('setting')) {
    function setting($key, $index = null)
    {
        $value = null;
        $setting = Valuestore::make(storage_path('app/settings.json'));

        if (($value = data_get($setting->get($key), $index)) != null) {
            return $value;
        }
        return $value;
    }
}

if (!function_exists('calculateOfferAmountByPercentage')) {
    function calculateOfferAmountByPercentage($productPrice, $offerPercentage)
    {
        $percentageResult = (floatval($offerPercentage) * floatval($productPrice)) / 100;
        return floatval($productPrice) - $percentageResult;
    }
}

if (!function_exists('convertDurationToTimeFormat')) {

    function convertDurationToTimeFormat($duration)
    {
        $hours = floor($duration / 60);
        $mins = $duration % 60;

        return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT) . ':00';
    }
}

if (!function_exists('color_theme')) {

    function color_theme($category)
    {
        return $category ? $category->color : '';
    }
}


// SAVE COOKIE with key and value
if (!function_exists('set_cookie_value')) {

    function set_cookie_value($key, $value, $expire = null)
    {
        $expire = $expire ?? time() + (2 * 365 * 24 * 60 * 60); // set a cookie that expires in 2 years
        setcookie($key, $value, $expire, '/');
        return true;
    }
}

// GET THE COOKIE value for Specific key
if (!function_exists('get_cookie_value')) {

    function get_cookie_value($key)
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
}

// Active Dashboard Menu
if (!function_exists('array_pluck')) {
    function array_pluck($object, $index)
    {
        return $object->pluck($index)->toArray();
    }
}


if (!function_exists('setValidationAttributes')) {

    function setValidationAttributes(array $attributes, $local = 'ar')
    {
        if (config('core.validation-attributes.' . $local)) {
            $attributes += (array)config('core.validation-attributes.' . $local);
            Illuminate\Support\Facades\Config::set('core.validation-attributes.' . $local, $attributes);
        }
    }
}

// Active Dashboard Menu
if (!function_exists('active_menu')) {
    function active_menu($routeNames)
    {
        $routeNames = (array) $routeNames;
        if(count(request()->segments()) == 3){
            return in_array(request()->segment(3), $routeNames) ? 'active' : '';
        }else{
            return   in_array(request()->segment(4), $routeNames) ? 'active' : '';
        }
    }
}



if (!function_exists('slugAr')) {
    /**
     * The Current dir
     *
     * @param  string  $locale
     */
    function slugAr($string, $separator = '-')
    {
        if (is_null($string)) {
            return "";
        }
        // Remove spaces from the beginning and from the end of the string
        $string = trim($string);
        // Lower case everything
        // using mb_strtolower() function is important for non-Latin UTF-8 string | more info: https://www.php.net/manual/en/function.mb-strtolower.php
        $string = mb_strtolower($string, "UTF-8");;
        // Make alphanumeric (removes all other characters)
        // this makes the string safe especially when used as a part of a URL
        // this keeps latin characters and arabic charactrs as well
        $string = preg_replace("/[^a-z0-9_\s\-ءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);
        // Remove multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        // Convert whitespaces and underscore to the given separator
        $string = preg_replace("/[\s_]/", $separator, $string);
        return $string;
    }
}


if (!function_exists('active_slide_menu')) {
    function active_slide_menu($routeNames)
    {
        $response = [];
        foreach ((array)$routeNames as $name) {
            array_push($response, active_menu($name));
        }

        return in_array('active', $response) ? 'active open' : '';
    }
}

if (!function_exists('calculateOfferAmountByPercentage')) {
    function calculateOfferAmountByPercentage($productPrice, $offerPercentage)
    {
        $percentageResult = (floatval($offerPercentage) * floatval($productPrice)) / 100;
        return floatval($productPrice) - $percentageResult;
    }
}


if (!function_exists('active_profile')) {

    function active_profile($route)
    {
        return (Route::currentRouteName() == $route) ? 'active' : '';
    }
}

// GET THE CURRENT LOCALE
if (!function_exists('locale')) {

    function locale()
    {
        return app()->getLocale();
    }
}

// CHECK IF CURRENT LOCALE IS RTL
if (!function_exists('is_rtl')) {

    function is_rtl($locale = null)
    {

        $locale = ($locale == null) ? locale() : $locale;

        if (in_array($locale, config('rtl_locales'))) {
            return 'rtl';
        }

        return 'ltr';
    }
}


if (!function_exists('slugfy')) {
    /**
     * The Current dir
     *
     * @param string $locale
     */
    function slugfy($string, $separator = '-')
    {
        $url = trim($string);
        $url = strtolower($url);
        $url = preg_replace('|[^a-z-A-Z\p{Arabic}0-9 _]|iu', '', $url);
        $url = preg_replace('/\s+/', ' ', $url);
        $url = str_replace(' ', $separator, $url);

        return $url;
    }
}


// if (! function_exists('path_without_domain')) {
//     /**
//      * Get Path Of File Without Domain URL
//      *
//      * @param string $locale
//      */
//     function path_without_domain($path)
//     {
//         return parse_url($path, PHP_URL_PATH);
//     }
// }


if (!function_exists('path_without_domain')) {
    /**
     * Get Path Of File Without Domain URL
     *
     * @param string $locale
     */
    function path_without_domain($path)
    {
        $url = $path;
        $parts = explode("/", $url);
        array_shift($parts);
        array_shift($parts);
        array_shift($parts);
        $newurl = implode("/", $parts);

        return $newurl;
    }
}

if (!function_exists('int_to_array')) {
    /**
     * convert a comma separated string of numbers to an array
     *
     * @param string $integers
     */
    function int_to_array($integers)
    {
        return array_map("intval", explode(",", $integers));
    }
}


if (!function_exists('combinations')) {

    function combinations($arrays, $i = 0)
    {

        if (!isset($arrays[$i])) {
            return array();
        }

        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                    array_merge(array($v), $t) :
                    array($v, $t);
            }
        }

        return $result;
    }
}


if (!function_exists('htmlView')) {
    /**
     * Access the OrderStatus helper.
     */
    function htmlView($content)
    {
        return
            '<!DOCTYPE html>
           <html lang="en">
             <head>
               <meta charset="utf-8">
               <meta http-equiv="X-UA-Compatible" content="IE=edge">
               <meta name="viewport" content="width=device-width, initial-scale=1">
               <link href="css/bootstrap.min.css" rel="stylesheet">
               <!--[if lt IE 9]>
                 <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
               <![endif]-->
             </head>
             <body>
               ' . $content . '
               <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
               <script src="js/bootstrap.min.js"></script>
             </body>
           </html>';
    }
}


if (!function_exists('currency')) {
    /**
     * The Current currency
     *
     * @param string $currency
     */
    function currency($price)
    {
        if (session()->get('currency'))
            return convertCurrency($price) . ' ' . currentCurrency();

        return convertCurrency($price) . ' ' . currentCurrency();
    }
}

if (!function_exists('convertCurrency')) {
    /**
     * The Convert Price
     *
     * @param string $price
     */
    function convertCurrency($price)
    {
        if (session()->get('currency'))
            return (round(($price * session()->get('currency')['rate']) / 5) * 5);

        if (Request::header('Currency-Rate'))
            return (round(($price * \Request::header('Currency-Rate')) / 5) * 5);

        return round($price);
    }
}

if (!function_exists('currentCurrency')) {
    /**
     * The Current currentCurrency
     *
     * @param string $currentCurrency
     */
    function currentCurrency()
    {
        if (session()->get('currency'))
            return session()->get('currency')['code'];

        if (Request::header('Currency-Rate'))
            return \Request::header('Currency-Code');

        return setting('default_currency');
    }
}

if (!function_exists('ajaxSwitch')) {

    function ajaxSwitch($model, $url, $switch = 'status', $open = 1, $close = 0)
    {
        return view('apps::dashboard.components.ajax-switch', compact('model', 'url', 'switch', 'open', 'close'))->render();
    }
}


if (!function_exists('checkRouteLocale')) {
    function checkRouteLocale($model, $slug)
    {
        if ($array = $model->getTranslations("slug")) {
            $locale = array_search($slug, $array);
            return $locale == locale();
        }
        return true;
    }
}

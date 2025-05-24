<?php

namespace Modules\Apps\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;
use Modules\Apps\Http\Requests\Frontend\ContactUsRequest;
use Modules\Apps\Notifications\Frontend\ContactUsNotification;
use Modules\Faq\Entities\Faq;
use Modules\Page\Entities\Page;

class AppsController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $offers = [];
        return view('apps::Frontend.index',compact('offers'));
    }

    public function about_us(){
        $page = Page::find(1);
        return view('apps::Frontend.about_us',compact('page'));
    }

    public function terms(){
        $page = Page::find(2);
        return view('apps::Frontend.terms',compact('page'));
    }

    public function faq(){
        $data = Faq::active()->orderBy('order','desc')->get();
        return view('apps::Frontend.faq',compact('data'));
    }

    public function contact_us(){
        return view('apps::Frontend.contact_us');
    }

    public function coming_soon(){
        return view('apps::Frontend.coming_soon');
    }

    public function post_contact_us(ContactUsRequest $request)
    {
        Notification::route('mail', config('setting.contact_us.email'))
            ->notify((new ContactUsNotification($request))->locale(locale()));

        return redirect()->back()->with(['msg' => __('apps::frontend.contact_us_page.alerts.send_message'),'status'=>'success']);
    }
}

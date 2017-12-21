<?php

namespace App\Http\Controllers;

use App\Mail\MailClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ParseController extends Controller
{
    public $myCurl;

    public function index(){
        return view('welcome');
    }

    public function activation()
    {
        set_time_limit(31536000);

        for ($i = 0; $i < 31536000; $i++) {

            $site = file_get_contents('https://coindar.org/ru/tweets');

            $data['date'] = $this->parse($site, '<div class="date">', '</div>');
            $data['image'] = $this->parseImage($site, '<div class="image"', '</div>');
            $data['twitter'] = $this->parseTwitter($site, '<div class="twitter-url">', '</div>');
            $data['caption'] = $this->parseCaption($site, '<div class="caption">', '</div>');
            $data['text'] = $this->parseText($site, '<div class="text">', '</div>');
            $data['ret'] = $this->parseRet($site, '<div class="ret">', '</div>');
            $data['fav'] = $this->parseFav($site, '<div class="fav">', '</div>');

            //Request for Telegram

            $request = [
                'chat_id' => '410773068',
                'parse_mode' => 'html',
                'text' => $data['date'] .' '.  $data['caption']['title'] .' '. $data['caption']['tag'].' '.$data['text']
            ];

            if (!isset($_SESSION['parse_data'])) {

                $this->send($data);

                $_SESSION['parse_data'] = $data;


                if ($_SESSION['parse_data']){
                    $this->myCurl = curl_init();
                    curl_setopt_array($this->myCurl, array(
                        CURLOPT_URL => 'https://api.telegram.org/bot416874933:AAFMb9PMRM826qvMo_J233pgvbCtoKfWI8s/sendMessage?',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query($request)
                    ));
                    $response = curl_exec($this->myCurl);
                    curl_close($this->myCurl);
                }
            }else {
                if ($_SESSION['parse_data'] !== $data) {

                    $this->send($data);

                    $_SESSION['parse_data'] = $data;

                    $this->myCurl = curl_init();
                    curl_setopt_array($this->myCurl, array(
                        CURLOPT_URL => 'https://api.telegram.org/bot416874933:AAFMb9PMRM826qvMo_J233pgvbCtoKfWI8s/sendMessage?',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query($request)
                    ));
                    $response = curl_exec($this->myCurl);
                    curl_close($this->myCurl);
                }
            }
            sleep(3);
        }
    }

    public function deactivation(){
        if (isset($_SESSION['parse_data'])){
            unset($_SESSION['parse_data']);
        }

        return redirect()->route('home')->withMessage('Deactivated');
    }

    public function parse($site, $start, $finish){
        $num1 = strpos($site, $start);
        if ($num1 == false) return 0;
        $num2 = substr($site, $num1);
        return strip_tags(substr($num2, 0, strpos($num2, $finish)));
    }
    public function parseImage($site, $start, $finish){
        $num1 = strpos($site, $start);
        if ($num1 == false) return 0;
        $num2 = substr($site, $num1);

        return $this->between("<div class=\"image\" style=\"background-image:url('", "');\"", substr($num2, 0, strpos($num2, $finish)));
    }

    public function parseTwitter($site, $start, $finish){
        $num1 = strpos($site, $start);
        if ($num1 == false) return 0;
        $num2 = substr($site, $num1);
        return $this->after("<div class=\"twitter-url\">", substr($num2, 0, strpos($num2, $finish)));
    }

    public function parseCaption($site, $start, $finish){
        $num1 = strpos($site, $start);
        if ($num1 == false) return 0;
        $num2 = substr($site, $num1);
        $caption['title'] = $this->between("<div class=\"caption\"><strong>", "</strong>", substr($num2, 0, strpos($num2, $finish)));
        $caption['tag'] = $this->between("<span>", "</span>", substr($num2, 0, strpos($num2, $finish)));
        $caption['subtag'] = $this->after("<br />", substr($num2, 0, strpos($num2, $finish)));
        return $caption;
    }

    public function parseText($site, $start, $finish){
        $num1 = strpos($site, $start);
        if ($num1 == false) return 0;
        $num2 = substr($site, $num1);
        return $this->after("<div class=\"text\">", substr($num2, 0, strpos($num2, $finish)));
    }

    public function parseRet($site, $start, $finish){
        $num1 = strpos($site, $start);
        if ($num1 == false) return 0;
        $num2 = substr($site, $num1);
        return $this->after("<div class=\"ret\">", substr($num2, 0, strpos($num2, $finish)));
    }

    public function parseFav($site, $start, $finish){
        $num1 = strpos($site, $start);
        if ($num1 == false) return 0;
        $num2 = substr($site, $num1);
        return $this->after("<div class=\"fav\">", substr($num2, 0, strpos($num2, $finish)));
    }

    function after ($after, $string){

        if (!is_bool(strpos($string, $after)))
            return substr($string, strpos($string,$after)+strlen($after));
    }

    function before ($before, $string){

        return substr($string, 0, strpos($string, $before));
    }

    function between ($after, $before, $string){

        return $this->before ($before, $this->after($after, $string));
    }

    public function send($data){
        Mail::to('kiddangel84@gmail.com')->send(new MailClass('tweet', 'coindar.org', 'oleg.ivanenko98@gmail.com', $data));
    }
}

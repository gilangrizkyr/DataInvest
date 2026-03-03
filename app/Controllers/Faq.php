<?php

namespace App\Controllers;

class Faq extends BaseController
{
    public function index()
    {
        return view('faq_modern', ['title' => 'FAQ - DataInvest']);
    }
}
<?php

namespace App\Controllers;

use App\Models\Good;

class GoodController extends Controller
{
    private $goodModel;

    public function __construct()
    {
        $this->goodModel = new Good();
    }

    public function index()
    {
        $goods = $this->goodModel->getGoodsWithFields();
        $this->view('goods/index', ['goods' => $goods]);
    }

    public function getGoods()
    {
        $goods = $this->goodModel->getGoodsWithFields();
        $this->json($goods);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{


    public function getPdf()
    {



        require_once __DIR__ . '/vendor/autoload.php';

        $mpdf = new Mpdf();
    }
}

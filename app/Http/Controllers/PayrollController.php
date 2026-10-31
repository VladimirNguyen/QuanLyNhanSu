<?php
namespace App\Http\Controllers;
use App\Services\PayrollSummary;
use Illuminate\Http\Request;
class PayrollController extends Controller
{
    public function index(Request $request, PayrollSummary $summary, $id = null)
    {
        $request->validate(['month'=>'nullable|date_format:Y-m']);
        $month = $request->input('month') ?: now()->format('Y-m');
        return view('luong.tong-hop', ['rows'=>$summary->forMonth($month, $id === null ? null : (int)$id), 'month'=>$month]);
    }
}

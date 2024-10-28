<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::orderBy('id', 'desc')->paginate(25);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'note' => 'required',
        ]);

        $add_by = Auth::user()->id;
        $transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->note = $request->note;
        $transaction->add_by = $add_by;
        $transaction->save();

        $bank = Bank::find(1);
        if($request->type == 1){
            $bank->balance = $bank->balance + $request->amount;
        }else{
            $bank->balance = $bank->balance - $request->amount;
        }
        $bank->save();        

        if($transaction){
            return redirect()->route('transactions.index')->with('status', 'Transaction saved successfully.');
        }
        return redirect()->route('transactions.index')->with('delete', 'Transaction store faild, try again.');
    }

    public function edit(Transaction $transaction)
    {
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'date' => 'required',
            'type' => 'required',
            'amount' => 'required',
            'note' => 'required',
        ]);

        $bank = Bank::find(1);
        if($transaction->type == 1 && $request->type == 1){
            $diff = $request->amount - $transaction->amount;
            $bank->balance = $bank->balance + $diff;
        }elseif($transaction->type == 2 && $request->type == 2){
            $diff = $request->amount - $transaction->amount;
            $bank->balance = $bank->balance - $diff;
        }elseif($transaction->type == 1 && $request->type == 2){
            $bank->balance = $bank->balance - $transaction->amount;
            $bank->balance = $bank->balance - $request->amount;
        }elseif($transaction->type == 2 && $request->type == 1){
            $bank->balance = $bank->balance + $transaction->amount;
            $bank->balance = $bank->balance + $request->amount;
        }else{
            return redirect()->route('transactions.index')->with('delete', 'Transaction update faild, try again.');
        }
        $bank->save();

        $edit_by = Auth::user()->id;
        $transaction->date = $request->date;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->note = $request->note;
        $transaction->edit_by = $edit_by;
        $transaction->save();

        if($transaction){
            return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
        }
        return redirect()->route('transactions.index')->with('delete', 'Transaction update faild, try again.');
    }

    public function destroy(Request $request)
    {
        $transaction = Transaction::find($request->data_id);
        if($transaction)
        {
            $bank = Bank::find(1);
            if($transaction->type == 1){
                $bank->balance = $bank->balance - $transaction->amount;
            }
            elseif($transaction->type == 2){
                $bank->balance = $bank->balance + $transaction->amount;
            }else{
                return redirect()->route('transactions.index')->with('delete', 'No Transaction found!.');
            }
            $bank->save();
            
            $transaction->delete();
            return redirect()->route('transactions.index')->with('delete', 'Transaction deleted successfully.');
        }
        else
        {
            return redirect()->route('transactions.index')->with('delete', 'No Transaction found!.');
        }
    }

    public function invoice($id)
    {
        $transaction = Transaction::find($id);
        $pdf = Pdf::loadView('transactions.invoice', compact('transaction'));
        return $pdf->stream('invoice_'. $id .'.pdf');
    }

    public function view(Transaction $transaction)
    {
        return view('transactions.view', compact('transaction'));
    }
}

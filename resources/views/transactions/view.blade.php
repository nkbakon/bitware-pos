@extends('layouts.app')
@section('bodycontent')

@if (session('status'))
    <div class="text-black m-2 p-4 bg-green-200">
        {{ session('status') }}
    </div>
@endif
@if (session('success'))
    <div class="text-black m-2 p-4 bg-yellow-200">
        {{ session('success') }}
    </div>
@endif
@if (session('delete'))
    <div class="text-black m-2 p-4 bg-red-200">
        {{ session('delete') }}
    </div>
@endif

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <a href="{{ route('transactions.index') }}" title="back" class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150" ><i class="fa-solid fa-angles-left"></i></a><br><br>
                <br>
                <h5 class="font-bold text-left text-gray-900">Transaction Details</h5><br>
                <span class="text-lg text-gray-700">Transaction ID : #{{ $transaction->id }}</span><br>
                <span class="text-lg text-gray-700">Type : @if($transaction->type == 2) Expense @else Income @endif</span><br>
                <span class="text-lg text-gray-700">Date : {{ $transaction->date }}</span><br>
                <span class="text-lg text-gray-700">Amount : {{ $transaction->amount }}</span><br><br><hr>
                <span class="text-lg text-gray-700">Note : {{ $transaction->note }}</span><br>
                <span class="text-lg text-gray-700">Add By : {{ $transaction->addby->fname }} {{ $transaction->addby->lname }}</span><br>
                <span class="text-lg text-gray-700">Add Date : {{ $transaction->created_at->format('Y-m-d') }}</span><br>
                <span class="text-lg text-gray-700">Last Edit By : {{ $transaction->editby->fname }} {{ $transaction->editby->lname }}</span><br>
                <span class="text-lg text-gray-700">Last Edit Date : {{ $transaction->updated_at->format('Y-m-d') }}</span><br>
            </div>
        </div>
    </div>
</div>

@endsection
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\CartItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Log;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CartController extends Controller
{
    public function index()
    {
        $items = Item::where('status', 1)->get();
        return view('cashier.index', compact('items'));
    }

    public function addToCart($id)
    {
        try {
            $item = Item::find($id);
            Log::channel('ajax_call')->info("=======addToCart AJAX START Item ID - " . $item->id);

            $cashier = Auth::user()->id;

            $get_cart = CartItem::where('item_id', $item->id)->where('cashier_id', $cashier)->first();
            if($get_cart == null){
                $cart = new CartItem();
                $cart->cashier_id = $cashier;
                $cart->item_id = $item->id;
                $cart->qty = 1;
                $cart->save();
            }else{
                $get_cart->cashier_id = $cashier;
                $get_cart->item_id = $item->id;
                $get_cart->qty = $get_cart->qty + 1;
                $get_cart->save();
            }
            
            $output = "";
            $output1 = "";
            $output2 = "";

            $cart_items = CartItem::where('cashier_id', $cashier)->get();
            $total = 0;
            foreach($cart_items as $cart_item){
                $item_detail = Item::find($cart_item->item_id);
                $total = $total + ($cart_item->qty * $item_detail->price);

                $output .= '<div class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">';
                $output .= '  <img src="' . asset('storage') . '/' . $item_detail->image . '" alt="' . $item_detail->name . '" class="rounded-lg h-10 w-10 bg-white shadow mr-2">';
                $output .= '  <div class="flex-grow">';
                $output .= '    <h5 class="text-sm">' . $item_detail->name . '</h5>';
                $output .= '    <p class="text-xs block">LKR. ' . $item_detail->price . '</p>';
                $output .= '  </div>';
                $output .= '  <div class="py-1">';
                $output .= '    <div class="w-28 flex gap-2 ml-2">';
                $output .= '      <button onclick="downQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '      <input type="number" value="' . $cart_item->qty . '" onkeyup="updateQty(' . $item_detail->id . ', this.value)" class="w-16 cartQty bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">';
                $output .= '      <button onclick="upQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '    </div>';
                $output .= '  </div>';
                $output .= '</div>';
            }

            $output1 .= '<span>' . count($cart_items) . '</span>';
            $output2 .= '<span>LKR. ' . number_format($total, 2) . '</span>';

            return response()->json(['output' => $output, 'output1' => $output1, 'output2' => $output2]);
        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======addToCart Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function clearCart()
    {
        try {
            Log::channel('ajax_call')->info("=======clearCart AJAX START");

            $cashier = Auth::user()->id;
            $delete_cart = CartItem::where('cashier_id', $cashier)->delete();            
            
            $output = "";
            $output1 = "";
            $output2 = "";

            $cart_items = CartItem::where('cashier_id', $cashier)->get();
            $total = 0;
            foreach($cart_items as $cart_item){
                $item_detail = Item::find($cart_item->item_id);
                $total = $total + ($cart_item->qty * $item_detail->price);

                $output .= '<div class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">';
                $output .= '  <img src="' . asset('storage') . '/' . $item_detail->image . '" alt="' . $item_detail->name . '" class="rounded-lg h-10 w-10 bg-white shadow mr-2">';
                $output .= '  <div class="flex-grow">';
                $output .= '    <h5 class="text-sm">' . $item_detail->name . '</h5>';
                $output .= '    <p class="text-xs block">LKR. ' . $item_detail->price . '</p>';
                $output .= '  </div>';
                $output .= '  <div class="py-1">';
                $output .= '    <div class="w-28 flex gap-2 ml-2">';
                $output .= '      <button onclick="downQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '      <input type="number" value="' . $cart_item->qty . '" onkeyup="updateQty(' . $item_detail->id . ', this.value)" class="w-16 cartQty bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">';
                $output .= '      <button onclick="upQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '    </div>';
                $output .= '  </div>';
                $output .= '</div>';
            }

            $output1 .= '<span>' . count($cart_items) . '</span>';
            $output2 .= '<span>LKR. ' . number_format($total, 2) . '</span>';

            return response()->json(['output' => $output, 'output1' => $output1, 'output2' => $output2]);
        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======clearCart Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function downQty($id)
    {
        try {
            $item = Item::find($id);
            Log::channel('ajax_call')->info("=======downQty AJAX START Item ID - " . $item->id);

            $cashier = Auth::user()->id;

            $get_cart = CartItem::where('item_id', $item->id)->where('cashier_id', $cashier)->first();
            if($get_cart->qty == 0){
                $get_cart->delete(); 
            }else{
                $get_cart->cashier_id = $cashier;
                $get_cart->item_id = $item->id;
                $get_cart->qty = $get_cart->qty - 1;
                $get_cart->save();
            }            
            
            $output = "";
            $output1 = "";
            $output2 = "";

            $cart_items = CartItem::where('cashier_id', $cashier)->get();
            $total = 0;
            foreach($cart_items as $cart_item){
                $item_detail = Item::find($cart_item->item_id);
                $total = $total + ($cart_item->qty * $item_detail->price);

                $output .= '<div class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">';
                $output .= '  <img src="' . asset('storage') . '/' . $item_detail->image . '" alt="' . $item_detail->name . '" class="rounded-lg h-10 w-10 bg-white shadow mr-2">';
                $output .= '  <div class="flex-grow">';
                $output .= '    <h5 class="text-sm">' . $item_detail->name . '</h5>';
                $output .= '    <p class="text-xs block">LKR. ' . $item_detail->price . '</p>';
                $output .= '  </div>';
                $output .= '  <div class="py-1">';
                $output .= '    <div class="w-28 flex gap-2 ml-2">';
                $output .= '      <button onclick="downQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '      <input type="number" value="' . $cart_item->qty . '" onkeyup="updateQty(' . $item_detail->id . ', this.value)" class="w-16 cartQty bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">';
                $output .= '      <button onclick="upQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '    </div>';
                $output .= '  </div>';
                $output .= '</div>';
            }

            $output1 .= '<span>' . count($cart_items) . '</span>';
            $output2 .= '<span>LKR. ' . number_format($total, 2) . '</span>';

            return response()->json(['output' => $output, 'output1' => $output1, 'output2' => $output2]);
        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======downQty Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function upQty($id)
    {
        try {
            $item = Item::find($id);
            Log::channel('ajax_call')->info("=======upQty AJAX START Item ID - " . $item->id);

            $cashier = Auth::user()->id;

            $get_cart = CartItem::where('item_id', $item->id)->where('cashier_id', $cashier)->first();
            $get_cart->cashier_id = $cashier;
            $get_cart->item_id = $item->id;
            $get_cart->qty = $get_cart->qty + 1;
            $get_cart->save();         
            
            $output = "";
            $output1 = "";
            $output2 = "";

            $cart_items = CartItem::where('cashier_id', $cashier)->get();
            $total = 0;
            foreach($cart_items as $cart_item){
                $item_detail = Item::find($cart_item->item_id);
                $total = $total + ($cart_item->qty * $item_detail->price);

                $output .= '<div class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">';
                $output .= '  <img src="' . asset('storage') . '/' . $item_detail->image . '" alt="' . $item_detail->name . '" class="rounded-lg h-10 w-10 bg-white shadow mr-2">';
                $output .= '  <div class="flex-grow">';
                $output .= '    <h5 class="text-sm">' . $item_detail->name . '</h5>';
                $output .= '    <p class="text-xs block">LKR. ' . $item_detail->price . '</p>';
                $output .= '  </div>';
                $output .= '  <div class="py-1">';
                $output .= '    <div class="w-28 flex gap-2 ml-2">';
                $output .= '      <button onclick="downQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '      <input type="number" value="' . $cart_item->qty . '" onkeyup="updateQty(' . $item_detail->id . ', this.value)" class="w-16 cartQty bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">';
                $output .= '      <button onclick="upQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '    </div>';
                $output .= '  </div>';
                $output .= '</div>';
            }

            $output1 .= '<span>' . count($cart_items) . '</span>';
            $output2 .= '<span>LKR. ' . number_format($total, 2) . '</span>';

            return response()->json(['output' => $output, 'output1' => $output1, 'output2' => $output2]);
        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======upQty Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function updateQty($id, $qty)
    {
        try {
            $item = Item::find($id);
            Log::channel('ajax_call')->info("=======updateQty AJAX START Item ID - " . $item->id);

            $cashier = Auth::user()->id;

            $get_cart = CartItem::where('item_id', $item->id)->where('cashier_id', $cashier)->first();
            $get_cart->cashier_id = $cashier;
            $get_cart->item_id = $item->id;
            $get_cart->qty = $qty;
            $get_cart->save();         
            
            $output = "";
            $output1 = "";
            $output2 = "";

            $cart_items = CartItem::where('cashier_id', $cashier)->get();
            $total = 0;
            foreach($cart_items as $cart_item){
                $item_detail = Item::find($cart_item->item_id);
                $total = $total + ($cart_item->qty * $item_detail->price);

                $output .= '<div class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">';
                $output .= '  <img src="' . asset('storage') . '/' . $item_detail->image . '" alt="' . $item_detail->name . '" class="rounded-lg h-10 w-10 bg-white shadow mr-2">';
                $output .= '  <div class="flex-grow">';
                $output .= '    <h5 class="text-sm">' . $item_detail->name . '</h5>';
                $output .= '    <p class="text-xs block">LKR. ' . $item_detail->price . '</p>';
                $output .= '  </div>';
                $output .= '  <div class="py-1">';
                $output .= '    <div class="w-28 flex gap-2 ml-2">';
                $output .= '      <button onclick="downQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '      <input type="number" value="' . $cart_item->qty . '" onkeyup="updateQty(' . $item_detail->id . ', this.value)" class="w-16 cartQty bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">';
                $output .= '      <button onclick="upQty(' . $item_detail->id . ')" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">';
                $output .= '        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                $output .= '          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />';
                $output .= '        </svg>';
                $output .= '      </button>';
                $output .= '    </div>';
                $output .= '  </div>';
                $output .= '</div>';
            }

            $output1 .= '<span>' . count($cart_items) . '</span>';
            $output2 .= '<span>LKR. ' . number_format($total, 2) . '</span>';

            return response()->json(['output' => $output, 'output1' => $output1, 'output2' => $output2]);
        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======updateQty Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function getItem($id)
    {
        try {
            if($id == 0){
                $items = Item::where('status', 1)->get();
            }else{
                $items = Item::where('id', $id)->get();
            }
            
            $output = "";
            foreach($items as $item){
                $output .= '<div role="button" class="select-none cursor-pointer transition-shadow overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg" title="' . $item->name . '" onclick="addToCart(' . $item->id . ')">';
                $output .= '  <img src="' . asset('storage') . '/' . $item->image . '" alt="' . $item->name . '" class="w-full">';
                $output .= '  <div class="flex pb-3 px-3 text-sm -mt-3">';
                $output .= '    <p class="flex-grow truncate mr-1">' . $item->name . '</p>';
                $output .= '    <p class="nowrap font-semibold">LKR. ' . $item->price . '</p>';
                $output .= '  </div>';
                $output .= '</div>';                
            }
            
            return response()->json(['output' => $output]);
        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======getItem Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function updateCash($cash)
    {
        try {
            Log::channel('ajax_call')->info("=======updateCash AJAX START cash - " . $cash);
            $cashier = Auth::user()->id;    
            
            $output = "";
            $output1 = "";
            $output2 = false;

            $cart_items = CartItem::where('cashier_id', $cashier)->get();
            $total = 0;
            $balance = 0;
            foreach($cart_items as $cart_item){
                $item_detail = Item::find($cart_item->item_id);
                $total = $total + ($cart_item->qty * $item_detail->price);
            }

            $balance = $cash - $total;
            if($balance >= 0){
                $output2 = true;
            }else{
                $output2 = false;
            }
            $output .= '<span>LKR. ' . number_format($total, 2) . '</span>';
            $output1 .= '<span>LKR. ' . number_format($balance, 2) . '</span>';

            return response()->json(['output' => $output, 'output1' => $output1, 'output2' => $output2]);
        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======updateCash Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function submitPayment(Request $request)
    {
        try {
            $cashier = Auth::user()->id;
            $cart_items = CartItem::where('cashier_id', $cashier)->get();

            $total = 0;
            foreach($cart_items as $cart_item){
                $item_detail = Item::find($cart_item->item_id);
                $total = $total + ($cart_item->qty * $item_detail->price);
            }
            
            $sale = new Sale();
            $sale->total = $total;
            $sale->cash = $request->cash;
            $sale->change = $request->cash - $total;
            $sale->cashier_id = $cashier;
            $sale->save();

            foreach($cart_items as $cart_item){
                $item = Item::find($cart_item->item_id);
                $sale_item = new SaleItem();
                $sale_item->sale_id = $sale->id;
                $sale_item->item_id = $item->id;
                $sale_item->item_price = $item->price;
                $sale_item->qty = $cart_item->qty;
                $sale_item->amount = $item->price * $cart_item->qty;
                $sale_item->cashier_id = $cashier;
                $sale_item->save();
            }
            $cart_items = CartItem::where('cashier_id', $cashier)->delete();
            
            return redirect()->route('cashier.index')->with([
                'sale_id' => $sale->id
            ]);

        } catch (\Exception $exception) {
            Log::channel('ajax_call')->info("=======submitPayment Error - " . $exception->getMessage() . ' - line - ' . $exception->getLine());
        }
    }

    public function bill(Request $request)
    {
        $sale = null;
        if($request->sale_id){
            $sale = Sale::find($request->sale_id);
        }

        $pdf = Pdf::loadView('cashier.bill', compact('sale'));
        return $pdf->stream('sale_receipt_'. $request->sale_id .'.pdf');
    }

    public function sales()
    {
        $sales = Sale::orderBy('id', 'desc')->paginate(25);
        return view('cashier.sale', compact('sales'));
    }

    public function destroy(Request $request)
    {
        $sale = Sale::find($request->data_id);
        if($sale)
        {
            $sale_items = SaleItem::where('sale_id', $sale->id)->delete();            
            $sale->delete();
            return redirect()->route('sales.index')->with('delete', 'Sale deleted successfully.');
        }
        else
        {
            return redirect()->route('sales.index')->with('delete', 'No Sale found!.');
        }
    }
}

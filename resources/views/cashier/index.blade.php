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
<div class="bg-blue-gray-50" x-data="initApp()" x-init="initDatabase()">
  <!-- noprint-area -->
  <div class="hide-print flex flex-row h-screen antialiased text-blue-gray-800">
    <!-- left sidebar -->
    

    <!-- page content -->
    <div class="flex-grow flex">
      <!-- store menu -->
      <div class="flex flex-col bg-blue-gray-50 h-full w-full py-4">
        <div class="flex px-2 flex-row relative">
          
          <!--
          <input
            type="text"
            class="bg-white rounded-3xl shadow text-lg full w-full h-16 py-4 pl-16 transition-shadow focus:shadow-2xl focus:outline-none"
            placeholder="Search items ..."
          /> -->
          <div class="relative inline-block w-full">
            <div class="absolute left-5 top-3 px-2 py-2 rounded-full bg-gray-400 text-white">
              <img src="{{ asset('assets/search.svg') }}" alt="Search Icon" class="w-5 h-5">  
            </div>
            <button id="dropdownButton" class="bg-white rounded-3xl shadow text-md w-full h-16 py-4 pl-16 text-left focus:outline-none">
              Filter Items...
            </button>
            <div id="dropdownMenu" class="absolute left-0 z-10 hidden bg-white shadow-lg rounded-3xl mt-1 w-full max-h-60 overflow-y-auto">
              <input type="text" id="filterInput" placeholder="Type to filter..." class="p-2 w-full rounded-3xl border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500" onkeyup="filterItems()">
              <div class="flex items-center p-2 hover:bg-gray-100 cursor-pointer" onclick="searchItem(0)">
                <span>All Items</span>
              </div>
              @foreach($items as $item)
              <div class="flex items-center p-2 hover:bg-gray-100 cursor-pointer item" onclick="searchItem({{ $item->id }})">
                <img src="{{ asset('storage') }}/{{ $item->image }}" alt="Item Image" class="w-5 h-5 mr-2">
                <span>{{ $item->name }} (LKR. {{ $item->price }})</span>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        <div class="h-full overflow-hidden mt-4">
          <div class="h-full overflow-y-auto px-2">

            <!-- Comment empty messages
            <div
              class="select-none bg-blue-gray-100 rounded-3xl flex flex-wrap content-center justify-center h-full opacity-25"
              x-show="products.length === 0"
            >
              <div class="w-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
                <p class="text-xl">
                  YOU DON'T HAVE
                  <br/>
                  ANY PRODUCTS TO SHOW
                </p>
              </div>
            </div>
            <div
              class="select-none bg-blue-gray-100 rounded-3xl flex flex-wrap content-center justify-center h-full opacity-25"
              x-show="filteredProducts().length === 0 && keyword.length > 0"
            >
              <div class="w-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-xl">
                  EMPTY SEARCH RESULT
                  <br/>
                  "<span x-text="keyword" class="font-semibold"></span>"
                </p>
              </div>
            </div>
            -->
            
            <div class="grid grid-cols-4 gap-4 pb-3" id="exItems">
              @foreach($items as $item) 
                <div
                  role="button"
                  class="select-none cursor-pointer transition-shadow overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg"
                  title="{{ $item->name }}"
                  onclick="addToCart({{ $item->id }})"
                >
                  <img src="{{ asset('storage') }}/{{ $item->image }}" alt="{{ $item->name }}">
                  <div class="flex pb-3 px-3 text-sm -mt-3">
                    <p class="flex-grow truncate mr-1">{{ $item->name }}</p>
                    <p class="nowrap font-semibold">LKR. {{ $item->price }}</p>
                  </div>
                </div>             
              @endforeach
            </div>
            <div class="grid grid-cols-4 gap-4 pb-3" id="showItems" style="display:none;">
            </div>
          </div>
        </div>
      </div>
      <!-- end of store menu -->

      <!-- right sidebar -->
      <div class="w-5/12 flex flex-col bg-blue-gray-50 h-full bg-white pr-4 pl-2 py-4">
        <div class="bg-white rounded-3xl flex flex-col h-full shadow">
          @php
            $cart_items = App\Models\CartItem::where('cashier_id', auth()->user()->id)->get();
          @endphp
          @if(sizeof($cart_items) == 0)
          <!-- empty cart -->
          <div id="emptyCart" class="flex-1 w-full p-4 opacity-25 select-none flex flex-col flex-wrap content-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p>
              CART EMPTY
            </p>
          </div>
          @endif

          
          <!-- cart items -->
          <div class="flex-1 flex flex-col overflow-auto">
            <div id="showCount" class="h-16 text-center flex justify-center" @if(sizeof($cart_items) < 1) style="display:none;" @endif>
              <div class="pl-8 text-left text-lg py-4 relative">
                <!-- cart icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div id="exItemCount" class="text-center absolute bg-gray-400 text-white w-5 h-5 text-xs p-0 leading-5 rounded-full -right-2 top-3">{{ count($cart_items)}}</div>
                <div id="itemCount" style="display:none;" class="text-center absolute bg-gray-400 text-white w-5 h-5 text-xs p-0 leading-5 rounded-full -right-2 top-3"></div>
              </div>
              <div class="flex-grow px-8 text-right text-lg py-4 relative">
                <!-- trash button -->
                <button onclick="clearCart()" class="text-blue-gray-300 hover:text-pink-500 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
            
            <div class="flex-1 w-full px-4 overflow-auto h-32" id="exCartItems">
              @php
                $cart_items = App\Models\CartItem::where('cashier_id', auth()->user()->id)->get();
                $total = 0;
              @endphp
              @foreach($cart_items as $cart_item)
                @php 
                  $item_detail = App\Models\Item::find($cart_item->item_id);
                  $total = $total + ($cart_item->qty * $item_detail->price);
                @endphp                
                <div class="select-none mb-3 bg-blue-gray-50 rounded-lg w-full text-blue-gray-700 py-2 px-2 flex justify-center">
                  <img src="{{ asset('storage') }}/{{ $item_detail->image }}" alt="{{ $item_detail->name }}" class="rounded-lg h-10 w-10 bg-white shadow mr-2">
                  <div class="flex-grow">
                    <h5 class="text-sm">{{ $item_detail->name }}</h5>
                    <p class="text-xs block">LKR. {{ $item_detail->price }}</p>
                  </div>
                  <div class="py-1">
                    <div class="w-28 flex gap-2 ml-2">
                      <button onclick="downQty({{ $item_detail->id }})" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                      </button>
                      <input type="number" value="{{ $cart_item->qty }}" onkeyup="updateQty({{ $item_detail->id }}, this.value)" class="w-16 cartQty bg-white rounded-lg text-center shadow focus:outline-none focus:shadow-lg text-sm">
                      <button onclick="upQty({{ $item_detail->id }})" class="rounded-lg text-center p-2 text-white bg-blue-gray-600 hover:bg-blue-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            <div class="flex-1 w-full px-4 overflow-auto" id="cartItems" style="display:none;">
            </div>
          </div>
          <!-- end of cart items -->

          <!-- payment info -->
          <div class="select-none h-auto w-full text-center pt-3 pb-4 px-4">
            <form action="{{ route('cashier.submitPayment') }}" method="POST">
            @csrf
              <div class="flex mb-3 text-lg font-semibold text-blue-gray-700">
                <div>TOTAL</div>
                <div class="text-right w-full"><span id="exTotal">LKR. {{ number_format($total, 2) }}</span><span id="total" style="display:none;"></span></div>
              </div>
              <div class="mb-3 text-blue-gray-700 px-3 pt-2 pb-3 rounded-lg bg-blue-gray-50">
                <div class="flex text-lg font-semibold">
                  <div class="flex-grow text-left">CASH</div>
                  <div class="flex text-right">
                    <div class="mr-2">LKR. </div>
                    <input onkeyup="updateCash(this.value)" id="cashPayment" name="cash" type="number" class="w-28 text-right bg-white shadow rounded-lg focus:bg-white focus:shadow-lg px-2 focus:outline-none" required>
                  </div>
                </div>
                <hr class="my-2">
                <div class="grid grid-cols-3 gap-2 mt-2">
                  <button onclick="addCash(20.00, event)" class="bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">+20.00</button>
                  <button onclick="addCash(50.00, event)" class="bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">+50.00</button>
                  <button onclick="addCash(100.00, event)" class="bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">+100.00</button>
                  <button onclick="addCash(500.00, event)" class="bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">+500.00</button>
                  <button onclick="addCash(1000.00, event)" class="bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">+1000.00</button>
                  <button onclick="addCash(5000.00, event)" class="bg-white rounded-lg shadow hover:shadow-lg focus:outline-none inline-block px-2 py-1 text-sm">+5000.00</button>
                </div>
              </div>
              <div id="changeValue" style="display:none;" class="flex mb-3 text-lg font-semibold bg-cyan-50 text-blue-gray-700 rounded-lg py-2 px-3">
                <div class="text-cyan-800">CHANGE</div>
                <div id="changeAmount" class="text-right flex-grow text-cyan-600">
                </div>
              </div>

              <div id="dueValue" style="display:none;" class="flex mb-3 text-lg font-semibold bg-pink-100 text-blue-gray-700 rounded-lg py-2 px-3">
                <div class="text-pink-800">DUE</div>
                <div id="dueAmount" class="text-right flex-grow text-pink-600">
                </div>
              </div>
              <button type="submit" disabled class="submitbtn disabled:opacity-50 bg-gray-800 text-white rounded-2xl text-lg w-full py-3 focus:outline-none">
                SUBMIT
              </button>
            </form>
          </div>
          <!-- end of payment info -->
        </div>
      </div>
      <!-- end of right sidebar -->
    </div>
  </div>
  <!-- end of noprint-area -->

  <div id="print-area" class="print-area"></div>
</div>

@endsection

@push('js')

@if(session('sale_id'))
  <script>
    window.onload = function() {
      // Open the bill page in a new tab
      window.open("{{ url('/cashier/bill?sale_id=' . session('sale_id')) }}", '_blank');
    };
  </script>
@endif

<script>
document.getElementById('cashPayment').addEventListener('input', function() {
  if (this.value < 0) {
    this.value = 0;
  }
});

document.querySelectorAll('.cartQty').forEach(function(input) {
  input.addEventListener('input', function() {
    if (this.value < 0) {
      this.value = 0;
    }
  });
});

document.getElementById('dropdownButton').onclick = function() {
  document.getElementById('dropdownMenu').classList.toggle('hidden');
};

window.onclick = function(event) {
  if (!event.target.matches('#dropdownButton') && !event.target.closest('#dropdownMenu')) {
    document.getElementById('dropdownMenu').classList.add('hidden');
  }
};

function filterItems() {
  const input = document.getElementById('filterInput').value.toLowerCase();
  const items = document.querySelectorAll('.item');

  items.forEach(item => {
    const text = item.innerText.toLowerCase();
    item.style.display = text.includes(input) ? 'flex' : 'none';
  });
}

function searchItem(id) {
  $.ajax({
    type: 'GET',
    url: '/cashier/getItem/' + id,
    success: function(data) {
      document.getElementById('dropdownMenu').classList.add('hidden');
      $('#showItems').show();
      $('#exItems').hide();
      $('#showItems').html(data.output);      
    },
    error: function(data) {
      console.log(data);
    }
  })
}

function addCash(amount, event) {
  event.preventDefault();

  const cashInput = document.getElementById('cashPayment');
  const currentCash = parseFloat(cashInput.value) || 0;
  const newCashValue = currentCash + amount;
  cashInput.value = newCashValue;

  const sound = new Audio();
  sound.src = '{{ asset("assets/sound/beep.mp3") }}';
  sound.play();
  sound.onended = () => {      
  };

  updateCash(newCashValue);
}

class CartManager {
  constructor() {
    this.cart = [];
    this.cash = 0;
    this.change = 0;
  }

  addToCart(id) {
    this.beep();
    $.ajax({
      type: 'GET',
      url: '/cashier/addToCart/' + id,
      success: function(data) {
        //console.log(data);
        $('#exCartItems').hide();
        $('#cartItems').show();
        $('#emptyCart').hide();
        $('#showCount').show();
        $('#cartItems').html(data.output);
        $('#exItemCount').hide();
        $('#itemCount').show();
        $('#itemCount').html(data.output1);
        $('#exTotal').hide();
        $('#total').show();
        $('#total').html(data.output2);

        document.querySelectorAll('.cartQty').forEach(function(input) {
          input.addEventListener('input', function() {
            if (this.value < 0) {
              this.value = 0;
            }
          });
        });
      },
      error: function(data) {
        //console.log(data);
      }
    })
  }

  clearCart() {
    this.clearSound();
    $.ajax({
      type: 'GET',
      url: '/cashier/clearCart',
      success: function(data) {
        $('#exCartItems').hide();
        $('#cartItems').show();
        $('#emptyCart').show();
        $('#showCount').hide();
        $('#cartItems').html(data.output);
        $('#exItemCount').hide();
        $('#itemCount').show();
        $('#itemCount').html(data.output1);
        $('#exTotal').hide();
        $('#total').show();
        $('#total').html(data.output2);

        document.querySelectorAll('.cartQty').forEach(function(input) {
          input.addEventListener('input', function() {
            if (this.value < 0) {
              this.value = 0;
            }
          });
        });
      },
      error: function(data) {
      }
    })
  }

  upQty(id) {
    this.beep();
    $.ajax({
      type: 'GET',
      url: '/cashier/upQty/' + id,
      success: function(data) {
        $('#exCartItems').hide();
        $('#cartItems').show();
        $('#emptyCart').hide();
        $('#showCount').show();
        $('#cartItems').html(data.output);
        $('#exItemCount').hide();
        $('#itemCount').show();
        $('#itemCount').html(data.output1);
        $('#exTotal').hide();
        $('#total').show();
        $('#total').html(data.output2);

        document.querySelectorAll('.cartQty').forEach(function(input) {
          input.addEventListener('input', function() {
            if (this.value < 0) {
              this.value = 0;
            }
          });
        });
      },
      error: function(data) {
      }
    })
  }

  downQty(id) {
    this.beep();
    $.ajax({
      type: 'GET',
      url: '/cashier/downQty/' + id,
      success: function(data) {
        $('#exCartItems').hide();
        $('#cartItems').show();
        $('#emptyCart').hide();
        $('#showCount').show();
        $('#cartItems').html(data.output);
        $('#exItemCount').hide();
        $('#itemCount').show();
        $('#itemCount').html(data.output1);
        $('#exTotal').hide();
        $('#total').show();
        $('#total').html(data.output2);

        document.querySelectorAll('.cartQty').forEach(function(input) {
          input.addEventListener('input', function() {
            if (this.value < 0) {
              this.value = 0;
            }
          });
        });
      },
      error: function(data) {
      }
    })
  }

  updateQty(id, qty) {
    this.beep();
    $.ajax({
      type: 'GET',
      url: '/cashier/updateQty/' + id + '/' + qty,
      success: function(data) {
        $('#exCartItems').hide();
        $('#cartItems').show();
        $('#emptyCart').hide();
        $('#showCount').show();
        $('#cartItems').html(data.output);
        $('#exItemCount').hide();
        $('#itemCount').show();
        $('#itemCount').html(data.output1);
        $('#exTotal').hide();
        $('#total').show();
        $('#total').html(data.output2);

        document.querySelectorAll('.cartQty').forEach(function(input) {
          input.addEventListener('input', function() {
            if (this.value < 0) {
              this.value = 0;
            }
          });
        });
      },
      error: function(data) {
      }
    })
  }

  updateCash(cash) {
    $.ajax({
      type: 'GET',
      url: '/cashier/updateCash/' + cash,
      success: function(data) {
        console.log(data);
        $('#exTotal').hide();
        $('#total').show();
        $('#total').html(data.output);
        if(data.output2 == true){
          $('#dueValue').hide();
          $('#changeValue').show();
          $('#changeAmount').html(data.output1);
          $(".submitbtn").attr('disabled', false);
        }else{
          $('#changeValue').hide();
          $('#dueValue').show();
          $('#dueAmount').html(data.output1);
          $(".submitbtn").attr('disabled', true);
        }

        document.querySelectorAll('.cartQty').forEach(function(input) {
          input.addEventListener('input', function() {
            if (this.value < 0) {
              this.value = 0;
            }
          });
        });
      },
      error: function(data) {
      }
    })
  }

  beep() {
    this.playSound('{{ asset("assets/sound/beep.mp3") }}');
  }

  clearSound() {
    this.playSound('{{ asset("assets/sound/button.mp3") }}');
  }

  playSound(src) {
    const sound = new Audio();
    sound.src = src;
    sound.play();
    sound.onended = () => {      
    };
  }
}

// Instantiate the CartManager in the global scope
const cartManager = new CartManager();

// Define a global function that calls the cartManager's method
window.addToCart = function(id) {
  cartManager.addToCart(id);
};

window.clearCart = function() {
  cartManager.clearCart();
};

window.upQty = function(id) {
  cartManager.upQty(id);
};

window.downQty = function(id) {
  cartManager.downQty(id);
};

window.updateQty = function(id, qty) {
  cartManager.updateQty(id, qty);
};

window.updateCash = function(cash) {
  cartManager.updateCash(cash);
};
</script>

@endpush
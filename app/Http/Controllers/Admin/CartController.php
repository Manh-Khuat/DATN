<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CartService;
use App\Models\Cart;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected $cart;
    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.carts.customer', [
           'title' => 'Danh Sách Đơn Đặt Hàng',
            'customers' => $this->cart->getCustomer()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $carts = $this->cart->getProductForCart($customer);

        return view('admin.carts.detail', [
            'title' => 'Chi Tiết Đơn Hàng: ' . $customer->name,
            'customer' => $customer,
            'carts' => $carts
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $contact = Customer::where('id', $request->input('id'))->first();
        if ($contact->active_flag == 1) {
            $contact->active_flag = 0;
            $contact->save();
            DB::table('carts')
                ->where('customer_id', $request->input('id'))
                ->update(['active_flag' => 0]);
            return response()->json([
                'error' => false,
                'message' => 'Giao Hàng Không Thành Công'
            ]);
        } elseif ($contact->active_flag == 0) {
            $contact->active_flag = 1;
            $contact->save();
            DB::table('carts')
                ->where('customer_id', $request->input('id'))
                ->update(['active_flag' => 1]);
            return response()->json([
                'error' => false,
                'message' => 'Giao Hàng Thành Công'
            ]);
        }

        return response()->json(['error' => true ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $customer = Customer::where('id', $request->input('id'))->first();
        if ($customer) {
            $customer->delete();
            return response()->json([
                'error' => false,
                'message' => 'Xóa đơn hàng thành công'
            ]);
        }

        return response()->json(['error' => true ]);
    }
}

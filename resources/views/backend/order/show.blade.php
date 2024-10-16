@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
  <h5 class="card-header">Order
    <a href="{{route('order.pdf', $order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a>
  </h5>
  <div class="card-body">
    @if ($order)
    <table class="table table-striped table-hover">
      @php
        $shipping_charge = DB::table('shippings')->where('id', $order->shipping_id)->pluck('price');
      @endphp
      <thead>
        <tr>
            <th>ID</th>
            <th>Order No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Quantity</th>
            <th>Charge</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            <td>@foreach($shipping_charge as $data) $ {{number_format($data, 2)}} @endforeach</td>
            <td>${{number_format($order->total_amount, 2)}}</td>
            <td>
                @if ($order->status == 'new')
                  <span class="badge badge-primary">{{$order->status}}</span>
                @elseif ($order->status == 'process')
                  <span class="badge badge-warning">{{$order->status}}</span>
                @elseif ($order->status == 'delivered')
                  <span class="badge badge-success">{{$order->status}}</span>
                @else
                  <span class="badge badge-danger">{{$order->status}}</span>
                @endif
            </td>
            <td>
                <a href="{{route('order.edit',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="Edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{route('order.destroy', [$order->id])}}">
                  @csrf
                  @method('delete')
                    <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} data-toggle="tooltip" data-placement="bottom" title="Delete" style="height:30px; width:30px;border-radius:50%"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
      </tbody>
      
    </table>


    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">ORDER INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>Order Number :</td>
                        <td>{{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>Order Date :</td>
                        <td>{{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                    <td>Ordered Product:</td>
                    @foreach ($order->cart_info as $cart)
                          @php
                            $product = DB::table('products')->select('title')->where('id', $cart->product_id)->get();
                          @endphp
                          @endforeach
                        <td>  @foreach ($product as $pro){{$pro->title}}
                               @endforeach</td>
                    
                    </tr>
                    <tr>
                        <td>Quantity :</td>
                        <td>{{$order->quantity}}</td>
                    </tr>
                    <tr>
                        <td>Order Status :</td>
                        <td>{{$order->status}}</td>
                    </tr>

                    <tr>
                      @php
                          $shipping_charge = DB::table('shippings')->where('id', $order->shipping_id)->pluck('price');
                      @endphp
                        <td>Shipping Charge :</td>
                        <td> $ {{number_format($shipping_charge[0], 2)}}</td>
                    </tr>
                    <tr>
                      <td>Coupon :</td>
                      <td> $ {{number_format($order->coupon, 2)}}</td>
                    </tr>
                    <tr>
                        <td>Total Amount :</td>
                        <td> $ {{number_format($order->total_amount, 2)}}</td>
                    </tr>
                    <tr>
                        <td>Payment Method :</td>
                        <td> @if($order->payment_method == 'cod') Cash on Delivery @elseif($order->payment_method == 'Paypal')  @else chapa @endif</td>
                    </tr>
                    <tr>
                        <td>Payment Status :</td>
                        <td> {{$order->payment_status}}</td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">SHIPPING INFORMATION</h4>
              <table class="table">
                <tr class="">
                    <td>Full Name :</td>
                    <td> {{$order->first_name}} {{$order->last_name}}</td>
                </tr>
                <tr>
                    <td>Email :</td>
                    <td> {{$order->email}}</td>
                </tr>
                <tr>
                    <td>Phone :</td>
                    <td> {{$order->phone}}</td>
                </tr>
                <tr>
                    <td>Address :</td>
                    <td>
                        {{$order->address1}}
                        @if (isset($order->address2))
                            OR {{ $order->address2}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Country :</td>
                    <td> {{$order->country}}</td>
                </tr>
                <tr>
                    <td>Post Code :</td>
                    <td> {{$order->post_code}}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  
    <section class="order_details pt-3">
    <div class="shipping-info">
              <h4 class="text-center pb-4">Order Details</h4>

    <table class="table">
      <thead>
        <tr>
          <th scope="col" class="col-6">Product</th>
          <th scope="col" class="col-3">Quantity</th>
          <th scope="col" class="col-3">Total</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($order->cart_info as $cart)
      @php
        $product = DB::table('products')->select('title')->where('id', $cart->product_id)->get();
      @endphp
        <tr>
          <td>
            <span>
              @foreach ($product as $pro)
                {{$pro->title}}
              @endforeach
            </span>
          </td>
          <td>x {{$cart->quantity}}</td>
          <td><span>${{number_format($cart->price, 2)}}</span></td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Subtotal:</th>
          <th scope="col"> <span>${{number_format($order->sub_total, 2)}}</span></th>
        </tr>
      {{-- @if(!empty($order->coupon))
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Discount:</th>
          <th scope="col"><span>-{{$order->coupon->discount(Helper::orderPrice($order->id, $order->user->id))}}{{Helper::base_currency()}}</span></th>
        </tr>
      @endif --}}
        <tr>
          <th scope="col" class="empty"></th>
          @php
            $shipping_charge = DB::table('shippings')->where('id', $order->shipping_id)->pluck('price');
          @endphp
          <th scope="col" class="text-right ">Shipping:</th>
          <th><span>${{number_format($shipping_charge[0], 2)}}</span></th>
        </tr>
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Total:</th>
          <th>
            <span>
                ${{number_format($order->total_amount, 2)}}
            </span>
          </th>
        </tr>
      </tfoot>
    </table>
    </div>
  </section>
 
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush

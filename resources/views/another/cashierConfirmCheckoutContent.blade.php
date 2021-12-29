<div>
    <span>Pembeli : <strong id="buyername"></strong></span>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>
                    no
                </th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            $totalHarga = 0;
            @endphp
            @foreach($data as $item)
            <tr>
                <td>{{$no}}</td>
                <td>{{$item->product->nama_product}}</td>
                <td>{{$item->buy_value}}</td>
                <td>@currency($item->custom_price)</td>
            </tr>
            @php
            $totalHarga += $item->custom_price;
            $no++;
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-info">
                <td colspan="3"><strong>Total Harga</strong></td>
                <td>@currency($totalHarga)</td>
            </tr>
        </tfoot>
    </table>
    <form action="{{ route('cashier.confirm.viacashier') }}" method="post">
        @csrf
        <input type="hidden" id="input_buyername" name="buyer_name">
        <input type="hidden" name="user_type" value="cns">
        <div class="form-group">
            <input type="checkbox" name="check" id="cekbrg" required>
            <label for="cekbrg">data sudah benar</label>
        </div>
        <button type="submit" class="btn btn-success btn-block">Konfirmasi & Cetak Faktur</button>
    </form>
</div>
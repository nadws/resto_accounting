<form id="myForm" action="" method="get">

        <input type="text" value="{{ request()->get('id_distribusi') }}" name="id_lokasi">
        <input type="text" value="{{ request()->get('id_lokasi') }}" name="id_distribusi">
        <input type="date" value="{{ request()->get('tgl1') }}" name="tgl1">
        <input type="date" value="{{ request()->get('tgl2') }}" name="tgl2">
        <button type="submit">Save</button><br><br>
    <div class="loading" style="display: none;">Loading...</div>
</form>
{{-- <form id="myForm" action="" method="get">

        <input type="text" value="{{ request()->get('id_distribusi') }}" name="id_lokasi">
        <input type="text" value="{{ request()->get('id_lokasi') }}" name="id_distribusi">
        <input type="text" value="" name="selected_month">
        <input type="text" value="" name="selected_year">
        <button type="submit">Save</button><br><br>
    <div class="loading" style="display: none;">Loading...</div>
</form> --}}
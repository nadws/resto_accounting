<form action="{{ route('import_jurnal') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">save</button>
</form>
<div>
    <div x-data="{
        rows: ['1'],
    }">
        <template x-for="(row, index) in rows" :key="index">
            <div class="row mt-2">
                {{ $slot }}

                <div class="col-lg-2">
                    <div class="form-group">
                        <label for="">Aksi</label><br>
                        <button class="btn btn-danger btn-sm me-2" type="button" @click="rows.splice(index, 1)"><i
                                class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </template>
        <button @click="rows.push({ value: '' })"  type="button" class="btn btn-block btn-lg"
                    style="background-color: #F4F7F9; color: #8FA8BD; font-size: 14px; padding: 13px;">
                    <i class="fas fa-plus"></i> Tambah Baris Baru

                </button>
    </div>
</div>


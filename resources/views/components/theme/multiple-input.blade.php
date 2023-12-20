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
        <button type="button" @click="rows.push({ value: '' })" class="btn btn-primary btn-sm mt-2">Tbh Baris</button>
    </div>
</div>


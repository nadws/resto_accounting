<x-theme.app title="{{$title}}" table="Y" sizeCard="12">

    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-2">

            </div>
        </div>

    </x-slot>

    <x-slot name="cardBody">
        <section class="row">
            <div class="col-lg-3">
                <label for="">Tanggal</label>
                <input type="date" class="form-control" name="tgl" value="{{date('Y-m-d')}}">
            </div>
            <div class="col-lg-3">
                <label for="">No Nota</label>
                <input type="text" class="form-control" name="tgl">
            </div>
            <div class="col-lg-12">
                <hr style="border: 1px solid black">
            </div>
            <div class="col-lg-12">
                <div id="load_menu"></div>
            </div>
            <div class="col-lg-6">
                <style>
                    input[type="checkbox"] {
                        appearance: none;
                        width: 40px;
                        height: 15px;
                        background: #81BFD9;
                        border-radius: 5px;
                        position: relative;
                        outline: 0;
                        cursor: pointer;
                    }

                    input[type="checkbox"]:before,
                    input[type="checkbox"]:after {
                        position: absolute;
                        content: "";
                        transition: all .25s;
                    }

                    input[type="checkbox"]:before {
                        width: 25px;
                        height: 25px;
                        background: #107AAE;
                        border: 5px solid #107AAE;
                        border-radius: 50%;
                        top: 50%;
                        left: 0;
                        transform: translateY(-50%);
                    }

                    input[type="checkbox"]:after {
                        width: 25px;
                        height: 25px;
                        background: #107AAE;
                        border-radius: 50%;
                        top: 50%;
                        left: 0px;
                        transform: scale(1) translateY(-50%);
                        transform-origin: 50% 50%;
                    }

                    input[type="checkbox"]:checked:before {
                        left: calc(120% - 25px);
                    }

                    input[type="checkbox"]:checked:after {
                        left: 75px;
                        transform: scale(0);
                    }
                </style>
                <div class="wrapper">
                    <input type="checkbox" class="onlain">
                </div>
            </div>
            <div class="col-lg-6">
                <hr style="border: 1px solid blue">
                <table class="" width="100%">
                    <tr>
                        <td width="20%">Total</td>
                        <td width="40%" class="total" style="text-align: right;">Rp.0</td>
                        <td width="40%" class="total_kredit" style="text-align: right;">Rp.0</td>
                    </tr>
                    <tr>
                        <td class="cselisih" colspan="2">Selisih</td>
                        <td style="text-align: right;" class="selisih cselisih">Rp.0</td>
                    </tr>
                </table>

            </div>
        </section>
    </x-slot>
    @section('scripts')
    <script src="/js/jurnal.js"></script>
    @endsection
</x-theme.app>
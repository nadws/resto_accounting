{{-- <div class="row">
    <div class="col-lg-12">
        <h5>{{$invoice->no_nota}}</h5>
    </div>
    @if (empty($grading))
    <div class="col-lg-4">
        <label for="">Tanggal</label>
        <input type="date" class="form-control" name="tgl" required>
    </div>
    <div class="col-lg-4">
        <label for="">No Campur</label>
        <input type="text" class="form-control" name="no_campur">
        <input type="hidden" class="form-control nota_grading" name="no_nota" required>
    </div>
    <div class="col-lg-4">
        <label for="">Gram Basah</label>
        <input type="text" class="form-control" name="gr_basah" value="0" required>
    </div>
    <div class="col-lg-4 mt-2">
        <label for="">Pcs Awal</label>
        <input type="text" class="form-control" name="pcs_awal" value="0" required>
    </div>
    <div class="col-lg-4 mt-2">
        <label for="">Gr Kering</label>
        <input type="text" class="form-control" name="gr_kering" value="0" required>
    </div>
    @else
    <div class="col-lg-4">
        <label for="">Tanggal</label>
        <input type="date" class="form-control" name="tgl" value="{{$grading->tgl}}" readonly>
    </div>
    <div class="col-lg-4">
        <label for="">No Campur</label>
        <input type="text" class="form-control" name="no_campur" value="{{$grading->no_campur}}"
            {{empty($grading->no_campur) ? '' : 'readonly'}}>
        <input type="hidden" class="form-control nota_grading" name="no_nota" value="{{$grading->no_nota}}">
    </div>
    <div class="col-lg-4">
        <label for="">Gram Basah</label>
        <input type="text" class="form-control" name="gr_basah" value="{{$grading->gr_basah}}" {{$grading->gr_basah ==
        '0' ? '' : 'readonly'}}>
    </div>
    <div class="col-lg-4 mt-2">
        <label for="">Pcs Awal</label>
        <input type="text" class="form-control" name="pcs_awal" value="{{$grading->pcs_awal}}" {{$grading->pcs_awal ==
        '0' ? '' : 'readonly'}}>
    </div>
    <div class="col-lg-4 mt-2">
        <label for="">Gr Kering</label>
        <input type="text" class="form-control" name="gr_kering" value="{{$grading->gr_kering}}" {{$grading->gr_kering
        ==
        '0' ? '' : 'readonly'}}>
    </div>
    @endif

</div> --}}

<div class="row">
    <div class="col-lg-12">
        <h5> Nota : {{$invoice->no_nota}}</h5>
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center">Tanggal</th>
                    <th style="text-align: center">No Grade</th>
                    <th style="text-align: right">Gr Basah</th>
                    <th style="text-align: right">Pcs Awal</th>
                    <th style="text-align: right">Gr Gdg Kering</th>
                    <th style="text-align: right">Susut Gram Beli / Kering</th>
                    <th style="text-align: right">Gr Kering / Basah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td align="center">{{date('d-m-Y',strtotime($grading->tgl))}}</td>
                    <td align="center">{{$grading->no_campur}}</td>
                    <td style="text-align: right">{{number_format($grading->gr_basah,0)}}</td>
                    <td style="text-align: right">{{number_format($grading->pcs_awal,0)}}</td>
                    <td style="text-align: right">{{number_format($grading->gr_kering,0)}}</td>
                    <td style="text-align: right">{{number_format((1 - ($grading->qty / $grading->gr_kering)) *
                        -100,0)}} %</td>
                    <td style="text-align: right">{{number_format((1 - ($grading->gr_kering / $grading->gr_basah)) *
                        100,0)}} %</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@extends('ksef.base')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form class="form-horizontal"
                  action="{{ route('ksef.create') }}"
                  method="POST"
                  enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                {{ csrf_field() }}

                <div class="mb-3">
                    <label for="nip">NIP firmy</label>
                    <input id="nip"
                           type="text"
                           class="form-control"
                           name="nip"
                           value="{{ $company->tax_id_number }}"
                           readonly>
                    <div class="form-control-feedback">{{ $errors->first('nip') }}</div>
                </div>

                <div class="mb-3{{ $errors->has('ksef_token') ? ' has-danger' : '' }}">
                    <label for="certificate">ksef token</label>
                    <input id="certificate"
                           type="text"
                           class="form-control form-control-danger"
                           name="ksef_token"
                           placeholder="20260429-EC-45EC48D000-E3B393FB5B-08|nip-9664421353|e966aa60892c4106a4e0fa0baad6c778d2df9f2d3bcf48a4a37c9e4f6927df01"
                    >
                    <div class="form-control-feedback">{{ $errors->first('ksef_token') }}</div>
                </div>

                <button class="btn btn-primary">
                    Zapisz
                </button>
            </form>
        </div>
    </div>
@endsection
<div class="form-group{{ $errors->has('company_name') ? ' has-danger' : '' }}">
    <label for="company_name" class="sr-only">Nazwa firmy</label>
    <input id="company_name" type="text" class="form-control form-control-danger" placeholder="Nazwa firmy" name="company_name" value="{{ old('company_name', $wizardData['company_name'] ?? '') }}" required autofocus>
    <div class="form-control-feedback">{{ $errors->first('company_name') }}</div>
</div>

<div class="form-group{{ $errors->has('tax_id_number') ? ' has-danger' : '' }}">
    <label for="tax_id_number" class="sr-only">Nip</label>
    <input id="tax_id_number" type="text" class="form-control form-control-danger" name="tax_id_number" value="{{ old('tax_id_number', $wizardData['tax_id_number'] ?? '') }}" placeholder="Nip">
    <div class="form-control-feedback">{{ $errors->first('tax_id_number') }}</div>
</div>

<div class="form-group{{ $errors->has('regon') ? ' has-danger' : '' }}">
    <label for="regon" class="sr-only">Regon</label>
    <input id="regon" type="text" class="form-control form-control-danger" name="regon" value="{{ old('regon', $wizardData['regon'] ?? '') }}" placeholder="Regon">
    <div class="form-control-feedback">{{ $errors->first('regon') }}</div>
</div>

<div class="form-group{{ $errors->has('bank_account') ? ' has-danger' : '' }}">
    <label for="bank_account" class="sr-only">Konto bankowe</label>
    <input id="bank_account" type="text" class="form-control form-control-danger" name="bank_account" value="{{ old('bank_account', $wizardData['bank_account'] ?? '') }}" placeholder="Konto bankowe">
    <div class="form-control-feedback">{{ $errors->first('bank_account') }}</div>
</div>
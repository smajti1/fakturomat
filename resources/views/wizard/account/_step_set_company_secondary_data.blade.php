<div class="form-group{{ $errors->has('company_email') ? ' has-danger' : '' }}">
    <label for="company_email">E-mail firmowy</label>
    <input id="company_email" type="text" class="form-control form-control-danger" name="company_email" value="{{ old('company_email') }}" placeholder="E-mail firmowy">
    <div class="form-control-feedback">{{ $errors->first('company_email') }}</div>
</div>
<div class="form-group{{ $errors->has('website') ? ' has-danger' : '' }}">
    <label for="website">Strona www</label>
    <input id="website" type="text" class="form-control form-control-danger" name="website" value="{{ old('website') }}" placeholder="Strona www">
    <div class="form-control-feedback">{{ $errors->first('website') }}</div>
</div>
<div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
    <label for="phone">Telefon</label>
    <input id="phone" type="text" class="form-control form-control-danger" name="phone" value="{{ old('phone') }}" placeholder="Telefon">
    <div class="form-control-feedback">{{ $errors->first('phone') }}</div>
</div>
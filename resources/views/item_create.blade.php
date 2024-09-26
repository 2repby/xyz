@extends('layout')
@section('content')
<div class="row justify-content-center">
    <div class="col-4">
    <form method="post" action={{url('item')}} >
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Наименование</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" aria-describedby="nameHelp" value={{ old('name') }}>
            <div id="nameHelp" class="form-text">Введите наименование товара (макс. 150 символов)  </div>
            @error('name')
            <div  class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Цена</label>
            <input type="text" class="form-control  @error('price') is-invalid @enderror"
                   id="price" name="price" aria-describedby="priceHelp" value={{ old('price') }}>
            <div id="priceHelp" class="form-text">Введите цену товара в рублях (целое число)</div>
            @error('price')
            <div  class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Категория</label>
            <select class="form-select" id="category" name="category_id" aria-describedby="categoryHelp" value={{ old('category_id') }} >
                <option style="display:none">
                @foreach ($categories as $category)
                    <option value="{{$category->id}}"
                            @if(old('category_id') == $category->id) selected
                        @endif>{{$category->name}}
                    </option>
                @endforeach
            </select>
            <div id="categoryHelp" class="form-text">Выберите категорию товара</div>
            @error('category_id')
            <div  class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
    </div>
</div>
@endsection


{{--<form method="post" action={{url('item')}}/>--}}
{{--    @csrf--}}
{{--    <label>Наименование</label>--}}
{{--    <input type="text" name="name" value="{{ old('name') }}"/>--}}
{{--    @error('name')--}}
{{--    <div class="is-invalid">{{ $message }}</div>--}}
{{--    @enderror--}}
{{--<br>--}}
{{--    <label>Цена</label>--}}
{{--    <input type="text" name="price" value="{{ old('price') }}"/>--}}
{{--    @error('price')--}}
{{--    <div class="is-invalid">{{ $message }}</div>--}}
{{--    @enderror--}}
{{--<br>--}}
{{--    <label>Категория:</label>--}}
{{--    <select name="category_id" value="{{ old('category_id') }}">--}}
{{--        <option style="display:none">--}}
{{--        @foreach ($categories as $category)--}}
{{--            <option value="{{$category->id}}"--}}
{{--                    @if(old('category_id') == $category->id) selected--}}
{{--                @endif>{{$category->name}}--}}
{{--            </option>--}}
{{--        @endforeach--}}
{{--    </select>--}}
{{--    @error('category_id')--}}
{{--    <div class="is-invalid">{{ $message }}</div>--}}
{{--    @enderror--}}
{{--<br>--}}
{{--    <input type="submit">--}}
{{--</form>--}}



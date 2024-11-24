@extends('layout')
@section('content')
    @parent
    <div class="container">
    <h2 class="m-3">Список товаров</h2>
    <table class="table m-3">
        <thead>
        <td>id</td>
        <td>Наименование</td>
        <td>Цена</td>
        <td>Категория</td>
        <td>Действия</td>
        </thead>
        @foreach ($items as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->price}}</td>
                <td>{{$item->category->name}}</td>
                <td><a href="{{url('item/destroy/'.$item->id)}}">Удалить</a>
                    <a href="{{url('item/edit/'.$item->id)}}">Редактировать</a>
                </td>
            </tr>
        @endforeach
    </table>

    <div class="d-flex justify-content-center mt-4">
    {{ $items->links() }}
    </div>
    </div>
@endsection


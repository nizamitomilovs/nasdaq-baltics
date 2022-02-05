@extends('layouts.app')

@section('content')
    <form class="search-stock" method="POST" action="/stock">
        @csrf
        <input type="search" value="{{ isset($stock) ?? '' }}" id="stock" name="stock" required>
        <button class="search-button" type="submit">Search</button>
        @if(isset($stock) && isset($error))
            <div style="color:red" class="alert alert-danger"><span> {{$error}} {{$stock}}</span></div>
        @endif
    </form>
    <rate-component :stock-prices="{{$stockPrices ?? 'null'}}"></rate-component>
@stop

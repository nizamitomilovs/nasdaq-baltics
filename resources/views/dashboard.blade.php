@extends('layouts.app')

@section('content')
    <form method="POST" action="/stock">
        @csrf
        <input type="search" id="stock" name="stock" required>
        <button class="search-button" type="submit">Search</button>
    </form>
    <rate-component :stock-prices="{{$stockPrices ?? 'null'}}"></rate-component>
@stop



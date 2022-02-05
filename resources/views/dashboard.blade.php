@extends('layouts.app')

@section('content')
    <form method="POST" action="/stock">
        @csrf
        <input type="search" value="{{ old('stock') ? old('stock') : '' }}" id="stock" name="stock" required>
        <button class="search-button" type="submit">Search</button>
        @if(session('error') !== null)
            <div style="color:red" class="alert alert-danger"><span>{{session('error')}} {{ old('stock')}}</span></div>
        @endif
    </form>
    <rate-component :stock-prices="{{$stockPrices ?? 'null'}}"></rate-component>
@stop



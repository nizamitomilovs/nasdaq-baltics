@extends('layouts.app')

@section('content')
<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>

                    <input class="checkbox" type="checkbox" {{session('register') !== null && !session('register') ? "checked" : ""}} id="reg-log" name="reg-log"/>
                    <label for="reg-log"></label>
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                                <div class="card-front">
                                    <div class="center-wrap">
                                        <form method="POST" action="/login">
                                            @csrf
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Log In</h4>
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-style"
                                                       value="{{ old('email') ? old('email') : '' }}"
                                                       placeholder="Your Email" id="email" autocomplete="off">
                                                <i class="input-icon uil uil-at"></i>
                                                @if(isset(session('error')['email']))
                                                    <div style="color:red" class=""><span>{{session('error')['email'][0]}}</span></div>
                                                @endif
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" name="password" class="form-style"
                                                       placeholder="Your Password" id="password" autocomplete="off">
                                                <i class="input-icon uil uil-lock-alt"></i>
                                                @if(isset(session('error')['password']))
                                                    <div style="color:red"><span>{{session('error')['password'][0]}}</span></div>
                                                @endif
                                            </div>
                                            @if(session('message') && session('register') === null)
                                                <div class="alert-danger mt-4">{{session('message')}}</div>
                                            @endif
                                            <button type="submit" class="btn mt-4">submit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-back">
                                    <div class="center-wrap">
                                        <form method="POST" action="/register">
                                            @csrf
                                        <div class="section text-center">
                                            <h4 class="mb-4 pb-3">Sign Up</h4>
                                            <div class="form-group">
                                                <input type="text" name="name" class="form-style"
                                                       placeholder="Your Full Name" id="name" autocomplete="off">
                                                <i class="input-icon uil uil-user"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="email" name="email" class="form-style"
                                                       placeholder="Your Email" id="email" autocomplete="off">
                                                <i class="input-icon uil uil-at"></i>
                                            </div>
                                            <div class="form-group mt-2">
                                                <input type="password" name="password" class="form-style"
                                                       placeholder="Your Password" id="password" autocomplete="off">
                                                <i class="input-icon uil uil-lock-alt"></i>
                                            </div>
                                            <button type="submit" class="btn mt-4">submit</button>
                                            @if(session('message') && session('register') !== null)
                                                <div class="alert-danger mt-4">{{session('message')}}</div>
                                            @endif
                                        </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@extends('layouts.app')

@section('content')

<!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
<div id="login-page">
    <div class="container">
        <div class='col-md-6'>
            <form class="form-login" method="POST" action="{{ route('admin.login') }}">
                @csrf
                <h2 class="form-login-heading">Admin Login</h2>
                <div class="login-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Admin ID" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            <br>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Admin Password" value="{{ old('password') }}" required autocomplete="current-password">
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
                        </div>
                    </div>
                </div>
                <hr>
            </form>
        </div>
        <div class='col-md-6'>
            <form class="form-login" method="POST" action="{{ route('client.login') }}">
                @csrf
                
                <h2 class="form-login-heading">Client Login</h2>
                <div class="login-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" placeholder="Client Mobile" value="{{ old('mobile') }}" required autocomplete="username" autofocus>
                                
                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            <br>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Client DOB" value="{{ old('password') }}" required>
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            
                            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
                            <a data-toggle="modal" class="btn btn-link" href="#myModal">
                                {{ __('Client Forgot Your Password?') }}
                            </a>
                        </div>
                    </div>
                </div>
              <!-- Modal -->
              <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title">Forgot Password ?</h4>
                    </div>
                    <div class="modal-body">
                      <p>Enter your e-mail address below to reset your password.</p>
                      <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                    </div>
                    <div class="modal-footer">
                      <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                      <button class="btn btn-theme" type="button">Submit</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- modal -->
            </form>
        </div>
    </div>
</div>

@endsection

@section('css')
    @parent
    
@stop

@section('scripts')
    @parent
    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="{{ config('app.asset_url') }}/lib/jquery.backstretch.min.js"></script>
    <script>
      $.backstretch("{{ config('app.asset_url') }}/images/login-bg.jpg", {
        speed: 500
      });
    </script>
@stop

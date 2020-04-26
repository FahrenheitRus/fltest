<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <!-- Styles -->


  <title>Проверка самозанятости</title>

  <body class="text-center">
    <form class="form-signin" method="POST" action="{{url('/')}}">

      {{ csrf_field() }}

      <img class="mb-4" src="data:image/png;base64,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" alt="" width="72" height="72">

      <h1 class="h3 mb-3 font-weight-normal">Пожалуйста введите ИНН</h1>
      <label for="inputINN" class="sr-only">ИНН</label>
      <input pattern="[0-9]{10-12}" id="inputINN" name="INN" class="h3 mb-4 form-control" maxlength="12" placeholder="ИНН" value="{{old('INN')}}" required autofocus>



      <button class="btn btn-lg btn-primary btn-block" type="submit">Проверить</button>

    </form>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">

                @foreach ($errors->all() as $error)
                   {{ $error }}
                @endforeach

        </div>
    @endif

    @isset($response)
          <div class="alert alert-success" role="alert">
            {{$response}}
          </div>
    @endisset

  </body>
</html>

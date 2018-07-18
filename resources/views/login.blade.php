<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ORSATMAX</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <h1>Login</h1>
      </div>
      <div class="row">
        <div class="input-group">
          <span class="input-group-addon" id="username"><i class="glyphicon glyphicon-envelope"></i></span>
          <input type="text" name="email" class="form-control" placeholder="Username" aria-describedby="username">
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="password"><i class="glyphicon glyphicon-asterisk"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Password" aria-describedby="password">
        </div>
      </div>
      <div class="row">
        <button type="button" id="login" class="btn btn-default">Login</button>
      </div>
    </div>

    <script type="text/javascript">
      $(function() {
        $('#login').click(function() {
          axios.post('{{url("api/authenticate")}}', {
            'email': $('[name="email"]').val(),
            'password': $('[name="password"]').val()
          })
          .then(function(response) {
            var token = response.data.token;
            Cookies.set('orsatmax_token', token);
          })
          .catch(function(error) {
            console.log(error);
          });
        });
      });
    </script>

  </body>
</html>

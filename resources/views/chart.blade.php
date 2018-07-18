<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ORSATMAX Chart</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- bootstrap-select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
  </head>
  <body>

    <div class="container">
      <h1>ORSATMAX Chart</h1>

      <div id="test">
        <label for="">Sites</label>
        <orsatmax-sites-dropdown></orsatmax-sites-dropdown>
        <hr>
        <label for="">Components (Single Selection)</label>
        <orsatmax-airs-dropdown v-bind:is-multiple="false"></orsatmax-airs-dropdown>
        <hr>
        <label for="">Components (Multiple Selection)</label>
        <orsatmax-airs-dropdown v-bind:is-multiple="true"></orsatmax-airs-dropdown>
      </div>
    </div>
    <script type="text/javascript">
      Vue.component('orsatmax-sites-dropdown', {
        data: function() {
          return {
            options: [ { id: null, instrument_name: 'Loading...' } ]
          }
        },
        mounted: function() {
          axios.get('http://localhost:8081/public/index.php/api/sites?token='+Cookies.get('orsatmax_token'))
          .then(response => (this.options = response.data.sites))
          .catch(error => console.log(error));
        },
        updated: function() {
          $(this.$refs.select).selectpicker('refresh');
        },
        template: `<select class="form-control" ref="select" multiple data-live-search="true">
            <option
              v-for="option in options"
              v-bind:value = "option.id">
              @{{option.instrument_name}}
            </option>
          </select>`
      });
      Vue.component('orsatmax-airs-dropdown', {
        props: ['isMultiple'],
        data: function() {
          return {
            options: [ { id: null, component_name: 'Loading...' } ]
          }
        },
        mounted: function() {
          axios.get('http://localhost:8081/public/index.php/api/airs?token='+Cookies.get('orsatmax_token'))
          .then(response => (this.options = response.data.airs))
          .catch(error => console.log(error));
        },
        updated: function() {
          $(this.$refs.select).selectpicker('refresh');
        },
        template: `<select class="form-control"
          ref="select"
          v-bind:multiple="isMultiple"
          data-live-search="true">
            <option
              v-for="option in options"
              v-bind:value = "option.id">
              @{{option.component_name}}
            </option>
          </select>`
      })
      var vm = new Vue({
        el: '#test'
      });
    </script>

  </body>
</html>

@extends('backend.template.layout')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Notifications</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Homepage</a></li>
        <li class="active">Notifications</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
          @if (session('alert-success'))
            <div class="callout callout-success">
              <b>{!! session('alert-success') !!}</b>
            </div>
          @endif
          @if (session('alert-warning'))
            <div class="callout callout-warning">
              <b>{!! session('alert-warning') !!}</b>
            </div>
          @endif
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Push Notification</h3>
              </div><!-- /.box-header -->

              <div class="box-body">

                   <div class="form-group">
                    <label>Language</label>
                    <select name="aws_sns_topic_id" id="aws_sns_topic_id">
                      @foreach($topics as $topic)
                        <?php
                          $display_topic_name = array(
                              'notification_cn' => '簡體',
                              'notification_hk' => '繁體',
                              'notification_en' => 'English',
                          );
//                          dd($display_topic_name);
                        ?>
                        <option value="{{ $topic->id }}">{{ $display_topic_name[$topic->name] }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Message</label>
                    <input name="message" id="notification_message" type="text" class="form-control" placeholder="Please enter message..." required>
                  </div>

                  <div class="box-footer" style="text-align: right;">
                    <button type="submit" class="btn btn-primary" onclick="cfm_notification()">Submit</button>
                  </div>

              </div>
            </div>
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List</h3>
            </div><!-- /.box-header -->

            <div class="box-body">

              <table id="notification-list" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Message</th>
                </tr>
                </thead>
                <tbody>

                @foreach($notifications as $notification)
                  <tr>
                    <td>{{ $notification->id }}</td>
                    <td>{{ $notification->topic->display_name }}</td>
                    <td>{{ $notification->message }}</td>
                  </tr>
                @endforeach

                </tbody>
              </table>
            </div><!-- /.box-body -->
          </div><!-- /.box -->
        </div>
      </div>

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables/dataTables.bootstrap.css') }}">

  <!-- DataTables -->
  <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- SlimScroll -->
  <script src="{{ asset('admin/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ asset('admin/plugins/fastclick/fastclick.min.js') }}"></script>

  <script>
      $(function () {
          $("#notification-list").DataTable({
              "order": [[0, "desc"]]
          });
      });

      function cfm_notification() {
          var display_name = $('#aws_sns_topic_id option:selected').text();
          var aws_sns_topic_id = $('#aws_sns_topic_id').val();
          var message = $('#notification_message').val();
          var cfm = confirm("Are you sure push notification to "+display_name+"\n"+message);

          if(cfm === false) {
              return false;
          } else {
              $.ajax({
                  method: "POST",
                  url: "{{ action('Backend\NotificationController@store') }}",
                  data: { aws_sns_topic_id: aws_sns_topic_id, message: message },
                  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                  success: function(data) {

                      var newRow = "<tr role=\"row\" class=\"odd warning\">\
                          <td class=\"sorting_1\">"+data.id+"</td>\
                          <td>"+display_name+"</td>\
                          <td>"+data.message+"</td>\
                      </tr>";
                      $('#notification-list tbody').prepend(newRow);
                      $('#notification_message').val('');
                  },
                  error: function(data) {
                     alert('Something Wrong!');
                  }

              })
          }

      }

  </script>
@endsection


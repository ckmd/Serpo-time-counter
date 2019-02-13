<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<?php    
$message = null;
if(isset($_POST['SubmitButton'])){ //check if form was submitted
  $input = $_POST['inputText']; //get input text
  $message = "Success! You entered: ".$input;
}    
?>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Serpo Performance</title>
  <!-- <link rel="stylesheet" href="{{asset('css/app.css')}}"> -->
  <link rel ="stylesheet" href="{{URL::asset('css/app.css')}}">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" id="app">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="/home" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Serpo </b>Performance</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('images/avatar04.png')}}" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->name}}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <li><a href="{{url('excel')}}"><i class="fa fa-upload"></i> <span>Upload Excel</span></a></li>
        <li><a href="{{url('allData')}}"><i class="fa fa-table"></i> <span>All Data</span></a></li>
        <li><a href="{{url('home')}}"><i class="fa fa-map-o"></i> <span>Filter By Region</span></a></li>
        <li><a href="{{url('national')}}"><i class="fa fa-globe"></i> <span>Filter Nasional</span></a></li>
        <li>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                              <i class="fa fa-sign-out"></i>
                <span>{{ __('Logout') }}</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        @yield('content')
<!-- <?php
print "message : ".$message;
?> -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="#">Indonesia Comnet Plus</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- <script src="{{asset('js/app.js')}}"></script> -->
<script type = "text/javascript" src = "{{URL :: asset ('js/app.js')}}"> </script>
</body>
</html>
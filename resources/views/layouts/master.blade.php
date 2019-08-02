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
  @yield('header')

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
          <img src="{{asset('images/fav.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="pull-left info" style="text-transform: capitalize;">
          <p>{{Auth::user()->name}}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <li class="treeview">
          <a href="#"><i class="fa fa-line-chart"></i><span>Serpo Performance</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            @can('isAdmin')
            <li><a href="{{url('excel')}}"><i class="fa fa-circle-o"></i> <span>Upload Raw Data Serpo</span></a></li>
            <!-- <li><a href="{{url('gangguan')}}"><i class="fa fa-circle-o"></i> <span>Daftar Gangguan</span></a></li>
            <li><a href="{{url('kendala')}}"><i class="fa fa-circle-o"></i> <span>Daftar Kendala</span></a></li> -->
            @endcan
            <li><a href="{{url('allData')}}"><i class="fa fa-circle-o"></i> <span>All Calculated Data</span></a></li>
            <li><a href="{{url('home')}}"><i class="fa fa-circle-o"></i> <span>Performance By Region</span></a></li>
            <li><a href="{{url('national')}}"><i class="fa fa-circle-o"></i> <span>Performance Nasional</span></a></li>
          </ul>
        </li>
        <!-- Icon Cadangan upload, table, map-o, globe, ban, spinner, circle-o -->
        <!-- <li class="treeview">
          <a href="#"><i class="fa fa-warning"></i><span>Gangguan dan Kendala</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
          </ul>
        </li> -->
        <li class="treeview">
          <a href="#"><i class="fa fa-wrench"></i><span>Preventive Maintenance</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            @can('isAdmin')
            <li><a href="{{url('prevMain')}}"><i class="fa fa-circle-o"></i> <span>Upload Raw Data PM</span></a></li>
            <li><a href="{{url('kategoriPM')}}"><i class="fa fa-circle-o"></i> <span>Daftar Kategori PM</span></a></li>
            @endcan
            <li><a href="{{url('prevMainData')}}"><i class="fa fa-circle-o"></i> <span>Data Calculated PM</span></a></li>
            <!-- <li><a href="{{url('popPrevMainData')}}"><i class="fa fa-circle-o"></i> <span>Report PM POP</span></a></li> -->
            <li><a href="{{url('report')}}"><i class="fa fa-circle-o"></i> <span>Report PM POP</span></a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-building"></i><span>Asset</span> <i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            @can('isAdmin')
            <li><a href="{{url('asset')}}"><i class="fa fa-circle-o"></i> <span>Upload Raw Data Asset</span></a></li>
            @endcan
            <li><a href="{{url('assetData')}}"><i class="fa fa-circle-o"></i> <span>Daftar Asset</span></a></li>
          </ul>
        </li>
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
@yield('footer')
</body>
</html>
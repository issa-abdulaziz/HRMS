<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

@include("Auth.layout.head")
<!-- END: Head-->

<!-- BEGIN: Body-->
<style>
    .headerLogo {
        width: 15%;
        height: 100%;
    }
</style>

@yield('content')

@include('Auth.layout.scripts')
<!-- END: Body-->

</html>

 <!-- BEGIN: Vendor JS-->
 <script src="{{ asset('vuexy/app-assets/vendors/js/vendors.min.js') }}"></script>
 <!-- BEGIN Vendor JS-->

     <!-- BEGIN: Page Vendor JS-->
     <script src="{{ asset('vuexy/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
     <!-- END: Page Vendor JS-->

 <!-- BEGIN: Page Vendor JS-->
 <script src="{{ asset('vuexy/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
 <!-- END: Page Vendor JS-->

 <!-- BEGIN: Theme JS-->
 <script src="{{ asset('vuexy/app-assets/js/core/app-menu.js') }}"></script>
 <script src="{{ asset('vuexy/app-assets/js/core/app.js') }}"></script>
 <!-- END: Theme JS-->


    <!-- BEGIN: Page JS-->
    <script src="{{ asset('vuexy/app-assets/js/scripts/forms/form-select2.js') }}"></script>
    <!-- END: Page JS-->

 <!-- BEGIN: Page JS-->
    <script src="{{ asset('vuexy/app-assets/js/scripts/pages/page-auth-login.js') }}"></script>
 <!-- END: Page JS-->



 <script>
     $(window).on('load', function() {
         if (feather) {
             feather.replace({
                 width: 14,
                 height: 14
             });
         }
     })
 </script>

@if($errors->any())
@foreach ($errors->all() as $error )
    <script>
        toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
        toastr.error("{{ $error }}");
    </script>
@endforeach
@endif



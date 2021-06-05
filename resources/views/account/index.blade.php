@extends('layouts.app')

@section('title')
员工账户管理
@endsection

@section('extraStyle')
<style type="text/css">
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 15px;
        width: 15px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(18px);
        -ms-transform: translateX(18px);
        transform: translateX(18px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 35px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
@endsection('extraStyle')

@section('extraScript')
<script type="text/javascript">
    function showAlert() {
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
        $('.alert').slideDown(500);
        setTimeout(function() {
            hideAlert();
            setTimeout(function() {
                location.reload();
            }, 2000);
        }, 3000);
    }

    function hideAlert() {
        $('.alert').slideUp(1000);
    }

    var closeAlertHtml = '<a href="#" class="close" onclick="hideAlert()">&times;</a>';

    function showAddAccountSuccessAlert() {
        var fullName = $('#addAccountModal #addAccountNewFullNameControl').val();

        $('.alert').html('新员工 ' + fullName + ' 添加成功！' + closeAlertHtml);
        showAlert();
    }

    function showEditAccountSuccessAlert() {
        var fullName = $('#editAccountModal #editAccountFullNameControl').val();

        $('.alert').html('员工 ' + fullName + ' 已被更新。' + closeAlertHtml);
        showAlert();
    }

    function showDeleteAccountSuccessAlert() {
        var fullName = $('#deleteAccountModal #deleteAccountFullNameDisplay').html();

        $('.alert').html('员工 ' + fullName + ' 已被删除。' + closeAlertHtml);
        showAlert();
    }

</script>
@endsection('extraScript')

@section('content')

<main class="container">
    <div class="h1">员工账户管理</div>

    <!-- Alert Message -->
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissable" role="alert">
        {!! nl2br(e(session('message'))) !!}
    </div>
    @endif

    <div class="row-flex d-flex justify-content-end w-100 my-2">
        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addAccountModal">
            <i class="icofont icofont-ui-add icofont-1x mr-2"></i>添加员工账户
        </button>
    </div>

    <users :users='{{ $users }}' :permissions={{ json_encode($permissions, true) }}></users>
    
</main>

@endsection
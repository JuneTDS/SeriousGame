@extends('layouts.backendSystem_layout')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>

@section('content')

<div class="">
    <div class="header-row">
        <div class="left"><h3>{{ $userData->username }}</h3></div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Popup Form -->
    <div id="success-popup" class="popup-form">
        <div class="row justify-content-center align-items-center ">
            <div class="success-warning-icon">
                <i class="fa fa-check"></i>
            </div>
        </div>
        <div class="row justify-content-center align-items-center " style="padding-top:42px">
            <p class="text-center">Changes have been saved successfully.</p>
        </div>
    </div>

    <!--  user info start -->
    <div class="row" style="padding-top:30px">
        <div class="col-md-3">
            <!-- First column with account image -->
            <div class="row">
                <div class="account-info-view">
                    <div class="account-icon-view">
                        @if ($userData->profile != "null" && $userData->profile != null)
                        <img src="/upload/{{$userData->profile}}" style="
                            border-radius: 50%;
                            width: 100px;
                        "/>
                        @else
                        <i class="fas fa-user" style="font-size: 30px; color: #737B7F;"></i>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row justify-content-center align-items-center">
                <label for="image-upload" class="btn btn-outline-dark" style="width:150px;margin-top:20px">Select Image</label>
                <input type="file" id="image-upload" style="display: none;"/>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row" style="padding-left:20px">
                <div class="col-md-9">
                    <form action="/admin/userProfileEditSave" method="post">
                        @csrf
                        <table class="table leftTable" style="border: none;">
                            <input type="hidden" id="userId" name="userId" value="{{ $userData->id }}">
                            <tr>
                                <td style="font-weight:bold">First Name</td>
                                <td><input type="text"  class="form-control" name="first_name" placeholder="Enter first name" value="{{ $userData->first_name }}" id="firstName"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Last Name</td>
                                <td><input type="text" class="form-control" name="last_name" placeholder="Enter last name" value="{{ $userData->last_name }}" id="lastName"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Email Address</td>
                                <td><input type="email" name="email" value="{{ $userData->email }}" id="email" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Email Gravatar</td>
                                <td><input type="email" name="email_gravatar" value="{{ $userData->email_gravatar }}" id="emailGravatar" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-size: 14px;">
                                    <p>To change the avatar, please use the <span style="font-weight: bold;" onclick="window.location.href='https://gravatar.com'">Gravatar</span> service.</p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <!-- Input field for User Role Name -->
                                <td><button class="btn btn-outline-dark" style="width:139px" id="update-profile-btn">Save</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  user info end -->


    <!-- start image crop popup -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <h5 class="modal-title" id="modalLabel"></h5> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end image crop popup -->

    <!--   password field start --><!-- <div class="header-row"></div> -->
    <div class="row" style="padding-top:30px">
        <h4 style="font-weight:normal">Update Password</h4>
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <!-- Second column with User Information -->
            <div class="row" style="padding-left:20px">
                <!-- First sub-column -->
                <div class="col-md-9">
                    <form action="/admin/userProfilePasswordSave" method="post">
                        @csrf
                        <table class="table" style="border: none;">
                            <input type="hidden" id="userId" value="{{ $userData->id }}">
                            <tr>
                                <td style="font-weight:bold">Current Password*</td>
                                <td><input type="password" id="current_Password" name="current_Password" placeholder="Enter current password" class="form-control"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">New Password*</td>
                                <td><input type="password" id="new_Password" name="new_Password" placeholder="Enter new password" class="form-control"></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold">Confirm Password*</td>
                                <td><input type="password" id="confirm_Password" name="confirm_Password" placeholder="Enter new password again" class="form-control"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><button class="btn btn-outline-dark" style="width:139px" id="update_psw_btn">Update</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- password field end -->
</div>

<!-- CSS for all backendSystem page -->
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/backendSystem.css">

<script src="{{ asset('assets/js/cropper.min.js') }}"></script>

<!-- Javascript for Popup -->
<script src="{{ asset('assets/js/backendSystem_UserPopup.js') }}"></script>

<script>
    var $modal = $('#modal');
    var image = document.getElementById('image');
    var cropper;

    let _token = $('meta[name="csrf-token"]').attr('content');

    $("body").on("change", "#image-upload", function(e){
        var files = e.target.files;
        var done = function (url) {
            image.src = url;
            $modal.modal('show');
        };

        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
            reader.readAsDataURL(file);
            }
        }
    });

    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 1,
            viewMode: 3,
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    $("#crop").click(function(){
        canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 160,
        });

        canvas.toBlob(function(blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result; 
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/user/uploadProfile",
                    data: {'_token': _token, 'image': base64data, 'userId': $("#userId").val()},
                    success: function(data){
                        console.log(data);
                        $modal.modal('hide');
                        alert("Crop image successfully uploaded");
                    }
                });
            }
        });
    });
</script>

@endsection
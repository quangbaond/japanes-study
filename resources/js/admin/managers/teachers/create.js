let MANAGER_TEACHER_CREATE = {};

$(function () {
    MANAGER_TEACHER_CREATE.init = function () {
        MANAGER_TEACHER_CREATE.handleChoiceImage();
        MANAGER_TEACHER_CREATE.clickCreateStudent();
        MANAGER_TEACHER_CREATE.clickClearImage();
        MANAGER_TEACHER_CREATE.countChar();
    };

    MANAGER_TEACHER_CREATE.handleChoiceImage = function() {
        // Choice image
        $("#choice_image").click(function () {
            $('#image_url').trigger('click');
        });

        // Change image
        $("#image_url").change(function () {
            if (this.files && this.files[0]) {
                $('#error-photo').html('');
                var pic_size = $('#image_url')[0].files[0].size/1024/1024;//get file size (MB)
                let reader = new FileReader();
                reader.onload = function (e) {
                    if (validImage("#image_url")) {
                        if(pic_size >= 5){
                            $('#error-photo').html('写真ファイルを5MB以下のサイズにしてください。');
                            $("#image_url").val(null);
                        }
                        else {
                            $('#image').attr('src', e.target.result);
                        }
                    }
                    else {
                        $('#error-photo').html('画像形式が正しくありません。対応する画像形式は（JPEG・JPG・PNG・GIF）です。');
                        $("#image_url").val(null);
                    }

                };
                reader.readAsDataURL(this.files[0]);
            }
        });
        // Valid Image
        function validImage(file_id) {
            let fileExtension = ['jpg', 'jpeg', 'png','gif'];
            let valid = true;
            let msg = "";
            if ($(file_id).val() == '') {
                valid = false;
            } else {
                var fileName = $(file_id).val();
                var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
                if ($.inArray(fileNameExt, fileExtension) == -1) {
                    valid = false;
                }
            }
            return valid //true or false
        }
    };

    MANAGER_TEACHER_CREATE.clickCreateStudent = function() {
        $('#btnCreateTeacher').click(function() {
            if(checkInternet()) {
                $('#formCreateTeacher').submit();
            }
        })
    }

    MANAGER_TEACHER_CREATE.clickClearImage = function() {
        $('#clearImage').click(function() {
            $('#image').attr('src', '/images/avatar_2.png');
            $("#image_url").val(null);
        })
    }
    MANAGER_TEACHER_CREATE.countChar = function() {
        $('#fieldIntro').on('keyup', function(){
            var len = this.value.length;
            if (len > 500) {
                this.value = this.value.substring(0, 500);
            } else {
                $('#charNum').text(len + "/500");
            }
        })

    }

    function checkInternet() {
        let ifConnected = window.navigator.onLine;
        if(ifConnected) {
            $('#area_message').html('');
            return true;
        }else {
            $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     更新が失敗しました。
                                </div>
                        </section>
                    `);
            $("html, body").animate({ scrollTop: 0 }, "slow");
        }
    }
});

$(document).ready(function () {
    MANAGER_TEACHER_CREATE.init();
});

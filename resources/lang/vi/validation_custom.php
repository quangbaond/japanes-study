<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'role' => [
        'free' => 'Miễn phí',
        'trial' => 'Dùng thử',
        'premium' => 'Premium',
        'special' => 'Đặc biệt',
        'other_company' => 'Công ty khác',
        'canceling_premium' => 'Đang huỷ Premium',
    ],
    'register_success' => 'Đăng ký thành công.',
    'register_error' => 'Đăng ký thất bại.',

    'input_error_common' => 'Giá trị đầu vào bị sai. Vui lòng thử lại.',
    'email_not_auth' => 'Email đã tồn tại trong hệ thống nhưng chưa được xác thực. Gửi mail để xác thực lại.',

    //M021
    'email_isset' => 'Địa chỉ email đã được đăng ký. Vui lòng đăng nhập hoặc sử dụng một địa chỉ email khác.',

    //M016
    'check_required' => 'Vui lòng chọn hộp này nếu bạn muốn tiếp tục.',
    'check_radio_required' => 'Vui lòng chọn 1 option',
    // CM0001
    'CM001' => 'Giá trị đầu vào bị sai. Vui lòng thử lại.',
    'CM002' => 'Đăng nhập thất bại.',
    'M001' => 'Trường :attribute không được bỏ trống.',
    'M002' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
    'M003' => 'Trường :attribute phải từ :min - :max ký tự.',
    'M004' => 'Trường :attribute chỉ có thể chứa chữ cái, số và ký hiệu.',
    'M005' => 'Thông tin đăng nhập không đúng. Vui lòng thử lại.',
    'M006' => 'Trường :attribute chỉ có thể chứa các chữ số.',
    'M007' => 'Đăng ký thất bại.',
    'M008' => 'Đăng ký thành công.',
    'M009' => 'Vui lòng nhập điều kiện tìm kiếm.',
    'M010' => 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.',
    'M011' => 'Không tìm thấy kết quả phù hợp với tìm kiếm của bạn.',
    'M012' => 'Hãy chọn 1 dòng đối tượng.',
    'M013' => 'Giá trị trường :attribute phải nhỏ hơn hoặc bằng :field.',
    'M014' => 'Xóa thành công.',
    'M015' => 'Bạn có chắc chắn muốn xóa các dòng đã chọn không?',
    'M016' => 'Vui lòng chọn hộp này nếu bạn muốn tiếp tục.',
    'M017' => 'Trường :attribute phải có tối thiểu :min ký tự.',
    'M018' => 'Trường :attribute không được lớn hơn :max ký tự.',
    'M019' => 'Kích thước hình ảnh không được vượt quá :sizeMB.',
    'M020' => 'Định dạng ngày không chính xác.',
    'M021' => 'Địa chỉ email đã được đăng ký. Vui lòng đăng nhập hoặc sử dụng một địa chỉ email khác.',
    'M022' => 'URL đã hết hạn. Vui lòng thực hiện lại.',
    'M023' => 'Địa chỉ email đã được đăng ký.',
    'M024' => 'Tệp hình ảnh không hợp lệ hoặc loại hình ảnh không được phép. Loại được phép: JPEG ・ JPG ・ PNG ・ GIF.',
    'M025' => 'Trường Nhập lại mật khẩu mật khẩu phải khớp.',
    'M026' => 'Trường Nhập lại email và email phải khớp.',
    'M027' => 'Cập nhật thành công.',
    'M028' => 'Cập nhật thất bại.',
    'M029' => 'Lịch học đang trùng lặp.',
    'M030' => 'Vui lòng nhập ít nhất một lịch học.',
    'M031' => 'Vui lòng nhập thời gian sau 30 phút kể từ bây giờ.',
    'M032' => 'Số thẻ của bạn không chính xác.',
    'M033' => 'Mã bảo mật thẻ của bạn không hợp lệ.',
    'M034' => 'Đây không phải là một liên kết YouTube',
    'M035' => 'Mật khẩu hiện tại không đúng.',
    'M036' => 'Email đã xoá bởi người quản trị, liên hệ với quản trị viên để được hỗ trợ.',
    'M037' =>'Bạn đã được đổi thành thành viên Miễn phí. Nhấp vào <a href="'.route('student.payment.premium').'" style="text-decoration: underline">đây</a> để mua lại gói premium.',
    'M038_1' => 'Số xu còn lại của bạn không đủ',
    'M038_2' => ' nhấp vào đây để mua thêm xu.',
    'M038' => 'Số xu còn lại của bạn không đủ,<a href="' . route("student.add-coin") . '">  nhấp vào đây để mua thêm xu.</a> ',
    'M039' => 'Đặt trước lịch học thành công.',
    'M040' => ['content'=>'Bạn đã được đặt trước cho bài học mới.  <a href=":attribute">Bấm vào đây để xem lịch học.</a>',
        'title'=>'Thông báo đặt trước'],
    'M041' => 'Lịch học đã được đặt trước, vui lòng chọn lịch học khác.',
    'M042' => 'Chỉ có thể đặt chỗ trước 30 phút so với giờ bắt đầu lớp học.',
    'M043' => 'Giao dịch thành công.',
    'M044' => 'Hủy bài học thành công.',
    'M045' => 'Thời khóa biểu đã bị thay đổi bởi giáo viên, vui lòng chọn lịch học khác.',
    'M046' => 'Bạn có chắc chắn muốn thanh toán không?',
    'M047' => '<h5><i class="icon fas fa-exclamation-triangle"></i> Thanh toán này đã bị lỗi do vấn đề thẻ của bạn!</h5>Vui lòng liên hệ với công ty phát hành thẻ để biết thêm chi tiết hoặc sử dụng một thẻ khác.',
    'M048' => 'Lớp học hiện không có sẵn.',
    'M049' => 'Địa chỉ email chưa xác thực.',
    'M050' => 'Vì đang trong quá trình thanh toán nên bạn không thể hủy.',
    'M051' => 'Bạn có chắc chắn muốn huỷ tư cách thành viên dùng thử không?',
    'M052' => 'Bạn có chắc chắn muốn huỷ tư cách thành viên Premium không?',
    'M053' => 'Hủy tư cách thành viên thành công.',
    'M054' => 'Bạn không thể thực hiện thao tác này vì tư cách thành viên Premium của bạn đã hết hạn.',
    'M056' => 'Hãy  nhập chữ số lớn hơn 0.',
    'M057' => 'Thay đổi tên thành công.',
    'M058' => 'Thay đổi email thành công.',
    'M059' => 'Thay đổi password thành công.',
    'M060' => 'Hoàn xu thành công.',
    'M062' => 'Lịch học bạn chọn đã quá thời hạn thành viên premium.',
    'M067' => 'Bạn không có quyền truy cập vào chức năng này.',
    'M069' => 'Cảm ơn bạn đã đánh giá.',
    'M070' => 'Không có dữ liệu tương ứng với điều kiện.',
    'M071' => 'Giáo viên này không dạy khoá mà bạn đang học. Vui lòng chọn khoá và bài học bạn mong muốn.',
    'M072' => 'Múi giờ hiện tại là ',
    'M073' => 'Vì bạn là thành viên miễn phí nên không thể xem nội dung của text và video. Bấm vào <a href="'.route('student.payment.premium').'" style="text-decoration: underline">đây</a> để đăng ký premium.',
    'M074' => 'Bạn đã hoàn thành học bài cuối cùng của chúng tôi. Bạn có thể chọn các bài học cũ để ôn tập lại.',
    'M075' =>'Vì bạn là thành viên miễn phí nên không thể sử dụng được xu. Bấm vào <a href="'.route('student.payment.premium').'"  style="text-decoration: underline"> đây </a> để đăng ký premium.',
    'M076' => 'Vì bạn là thành viên miễn phí nên không thể sử dụng được xu. Bấm vào <a href="'.route('student.payment.7-days-free-trial').'"  style="text-decoration: underline"> đây </a> để đăng ký 7 ngày dùng thử.',
    'M077' => 'Vì bạn là thành viên miễn phí nên không thể xem nội dung của text và video1. Bấm vào <a href="'.route('student.payment.7-days-free-trial').'"  style="text-decoration: underline"> đây </a> để đăng ký 7 ngày dùng thử.',
    'M078' => 'Thẻ của bạn đang dùng là thẻ để test',
];

let ADMIN_NOTIFICATION = {};
$(function() {
    ADMIN_NOTIFICATION.init = function() {
        ADMIN_NOTIFICATION.customTable()
        ADMIN_NOTIFICATION.eventCheckAll()
    }
    ADMIN_NOTIFICATION.customTable = function () {
        var oTable = $('#search-notification').DataTable({
            // ajax: 'https://gyrocode.github.io/files/jquery-datatables/arrays_id.json',
            language: {
                paginate: {
                    next: '<i class="fas fa-arrow-right"></i>',
                    previous: '<i class="fas fa-arrow-left"></i>'
                },

                sInfo : `_END_ / _TOTAL_`
            },
            lengthChange: false,
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            } ],
            order: [[ 1, 'asc' ]]
        });
        // search tabel
        $('#input-search').keyup(function(){
            oTable.search($(this).val()).draw();
        })

    }
    ADMIN_NOTIFICATION.eventCheckAll = function () {
        $("#check_all").change(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $(".checkSingle").click(function () {
            if ($(this).is(":checked")){
                var isAllChecked = 0;
                $(".checkSingle").each(function(){
                if(!this.checked)
                    isAllChecked = 1;
                })
                if(isAllChecked == 0){
                    $("#check_all").prop("checked", true);
                }
            }else {
                $("#check_all").prop("checked", false);
            }
        });
        // table changer content
        $("#search-notification").bind("DOMSubtreeModified", function() {
            var countRecord = $('tr').length
            var atLeastOneIsChecked = $('.checkSingle:checked').length + 1;
            if(countRecord == atLeastOneIsChecked){

                $("#check_all").prop("checked", true);
            }
            else {
                $("#check_all").prop("checked", false);
            }
        });
    }
})
$(document).ready(function() {
    ADMIN_NOTIFICATION.init();
});
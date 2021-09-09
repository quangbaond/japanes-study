const CURRICULUM_MANAGEMENT = {};
const routeGetListCurriculum = $('[name=route-data-tables]').attr('content');

$(function() {
    CURRICULUM_MANAGEMENT.init = function() {

    }

    CURRICULUM_MANAGEMENT.getData = function() {
        $(document).ready(function () {
            $('#table-curriculum').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "responsive": true,
                "pagingType": "full_numbers",
                "order": [[ 4, "desc" ]],
                'autoWidth'   : false,
                language: {
                    "url": "/Japanese.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: routeGetListCurriculum,
                    type: 'GET',
                    data: function (d) {
                        d.content           = $('#email').val();
                        d.from_date         = $('#from_date').val();
                        d.to_date           = $('#to_date').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'name', name: 'name', class : 'name' },
                    { data: 'level', name: 'level', class:'level'},
                    { data: 'description', name: 'description', class:'description' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false },
                ],
                "createdRow": function (row, data, rowIndex) {
                    $.each($('td[class=" name"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).html());
                    });
                    $.each($('td[class=" description"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).html());
                    });
                }
            });
        });
    }
})

$(document).ready()

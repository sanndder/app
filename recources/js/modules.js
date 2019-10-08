// Setup module
// ------------------------------
var Elements = function ()
{
    // Dropdown list
    var _lists = function () {
        //zorg er voor dat lijsten juiste element tonen
        $(document).on('click', '[data-ajax-list="true"] .dropdown-menu a', function(){
            //aangeklikte object
            $a = $(this);
            //alle weergeven
            $a.siblings().show();
            //aangeklikte verbergen
            $a.hide();
            //zichtbare element zoeken
            $el = $a.closest('[data-ajax-list="true"]').find('.dropdown-toggle');
            //html naar voren halen
            $el.html( $a.html() );
            //value wijzigen
            $el.closest('li').data('value', $a.data('value') );
        });
    };

    return {
        init: function ()
        {
            _lists();
        }
    }
}();



// Setup module
// ------------------------------
var SweetAlert = function ()
{
    //
    // Setup module components
    //

    // Sweet Alerts
    var _componentSweetAlert = function ()
    {
        if (typeof swal == 'undefined')
        {
            //console.warn('Warning - sweet_alert.min.js is not loaded.');
            return;
        }

        // Defaults
        var swalInit = swal.mixin({
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-light'
        });


        // Alert combination
        $('.sweet-confirm').on('click', function ()
        {
            var $btn = $(this);
            var delId = $btn.data('id');
            var $form = $btn.parents('form');
            console.log($form);
            var title = $btn.data('title');

            swalInit({
                title: title,
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ja, doorgaan!',
                cancelButtonText: 'Nee, annuleren!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function (result)
            {
                if (result.value)
                {
                    //add del id
                    $("<input />").attr("type", "hidden")
                        .attr("name", "del")
                        .attr("value", delId)
                        .appendTo($form);
                    $form.submit();
                }

            });
        });
    }

    return {
        initComponents: function ()
        {
            _componentSweetAlert();
        }
    }
}();


// Setup module
// ------------------------------

var Uniform = function ()
{

    //
    // Setup module components
    //

    // Uniform
    var _componentUniform = function ()
    {
        if (!$().uniform)
        {
            //console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.form-input-styled').uniform();

        // Danger
        $('.form-input-styled-danger').uniform({
            wrapperClass: 'border-danger-600 text-danger-800'
        });

        // Success
        $('.form-input-styled-success').uniform({
            wrapperClass: 'border-success-600 text-success-800'
        });

        // Warning
        $('.form-input-styled-warning').uniform({
            wrapperClass: 'border-warning-600 text-warning-800'
        });

        // Info
        $('.form-input-styled-info').uniform({
            wrapperClass: 'border-info-600 text-info-800'
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function ()
        {
            _componentUniform();
        }
    }
}();


// Setup module Datatables
// ------------------------------

var Datatables = function ()
{

    //
    // Setup module components
    //

    // Basic Datatable examples
    var _componentDatatableBasic = function ()
    {
        if (!$().DataTable)
        {
            //console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [
                {
                    orderable: false,
                    width: 100,
                    targets: [-1]
                },
                {
                    orderable: false,
                    visible: false,
                    searchable: false,
                    targets: [0]
                }
            ],
            lengthMenu: [
                [10, 15, 20, 25, 50, -1],
                ['10 rijen', '15 rijen', '20 rijen', '25 rijen', '50 rijen', 'Alle rijen']
            ],
            dom: '<"datatable-scroll"t><"datatable-footer"><"text-left"pl>',
            language: {
                search: '<span>Snel zoeken:</span> _INPUT_',
                searchPlaceholder: 'Type om te zoeken...',
                lengthMenu: '<span>Toon:</span> _MENU_',
                infoEmpty: 'Geen resultaten gevonden',
                zeroRecords: 'Geen gegevens beschikbaar voor tabel',
                emptyTable: 'Geen gegevens beschikbaar voor tabel',
                paginate: {
                    'first': 'Eerste',
                    'last': 'Laatste',
                    'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                    'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                }
            }
        });

        // Basic datatable
        $('.datatable-basic').DataTable({
            fnInitComplete: function (oSettings, json)
            {
                $("#DataTables_Table_0_length").detach().prependTo("#move-length-dropdown");
            }
        });


        // Resize scrollable table when sidebar width changes
        $('.sidebar-control').on('click', function ()
        {
            table.columns.adjust().draw();
        });

        //custom searchbox
        $('#datatable-search').keyup(function ()
        {
            $('.datatable-basic').DataTable().search($(this).val()).draw();
        });

    };

    // Select2 for length menu styling
    var _componentSelect2 = function ()
    {
        if (!$().select2)
        {
            //console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: 'auto'
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function ()
        {
            _componentDatatableBasic();
            _componentSelect2();
        }
    }
}();

// Setup module
// ------------------------------

var Datepickers = function() {

    // Pickadate picker
    var _componentPickadate = function() {
        if( !$().pickadate ){
            console.warn( 'Warning - picker.js and/or picker.date.js is not loaded.' );
            return;
        }

        // Basic options
        $( '.pickadate' ).pickadate({
            selectYears: true,
            selectMonths: true,
            close: '',
            selectYears: 10
        });

    };
    return {
        init: function() {
            _componentPickadate();
        }
    }
}();


var Select2Selects = function() {


    //
    // Setup module components
    //

    // Select2 examples
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Select with search
        $('.select-search').select2();

    };
    return {
        init: function() {
            _componentSelect2();
        }
    }
}();

// ------------------------------
// Initialize modules
// ------------------------------

document.addEventListener('DOMContentLoaded', function ()
{
    SweetAlert.initComponents();
    Uniform.init();
    Datatables.init();
    Elements.init();
    Datepickers.init();
    Select2Selects.init();
});
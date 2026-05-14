<link href="{{ asset('css/tailwind/app.css?v='.$asset_v) }}" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/vendor.css?v='.$asset_v) }}">

@if( in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl')) )
	<link rel="stylesheet" href="{{ asset('css/rtl.css?v='.$asset_v) }}">
@endif

@yield('css')

<!-- app css -->
<link rel="stylesheet" href="{{ asset('css/app.css?v='.$asset_v) }}">

@if(isset($pos_layout) && $pos_layout)
	<style type="text/css">
		.content{
			padding-bottom: 0px !important;
		}
	</style>
@endif
<style type="text/css">
	/*
	* Pattern lock css
	* Pattern direction
	* http://ignitersworld.com/lab/patternLock.html
	*/
	.patt-wrap {
	  z-index: 10;
	}
	.patt-circ.hovered {
	  background-color: #cde2f2;
	  border: none;
	}
	.patt-circ.hovered .patt-dots {
	  display: none;
	}
	.patt-circ.dir {
	  background-image: url("{{asset('/img/pattern-directionicon-arrow.png')}}");
	  background-position: center;
	  background-repeat: no-repeat;
	}
	.patt-circ.e {
	  -webkit-transform: rotate(0);
	  transform: rotate(0);
	}
	.patt-circ.s-e {
	  -webkit-transform: rotate(45deg);
	  transform: rotate(45deg);
	}
	.patt-circ.s {
	  -webkit-transform: rotate(90deg);
	  transform: rotate(90deg);
	}
	.patt-circ.s-w {
	  -webkit-transform: rotate(135deg);
	  transform: rotate(135deg);
	}
	.patt-circ.w {
	  -webkit-transform: rotate(180deg);
	  transform: rotate(180deg);
	}
	.patt-circ.n-w {
	  -webkit-transform: rotate(225deg);
	   transform: rotate(225deg);
	}
	.patt-circ.n {
	  -webkit-transform: rotate(270deg);
	  transform: rotate(270deg);
	}
	.patt-circ.n-e {
	  -webkit-transform: rotate(315deg);
	  transform: rotate(315deg);
	}

    /* Mobile responsiveness for Batch Modals and Tables */
    @media only screen and (max-width: 767px) {
        .modal-dialog.modal-xl, .modal-dialog.modal-lg {
            margin: 5px !important;
            width: calc(100% - 10px) !important;
        }
        .modal-body {
            padding: 8px !important;
        }
        
        /* Card-style stacking for tables */
        #pos_batch_select_table, .batch_details_modal table, #batch_details_table, #ledger_table {
            display: block;
            width: 100% !important;
            border: 0;
        }
        #pos_batch_select_table thead, .batch_details_modal table thead, #batch_details_table thead, #ledger_table thead {
            display: none;
        }
        #pos_batch_select_table tr, .batch_details_modal table tr, #batch_details_table tr, #ledger_table tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 12px;
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        #pos_batch_select_table td, .batch_details_modal table td, #batch_details_table td, #ledger_table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none !important;
            padding: 6px 0 !important;
            text-align: right;
            width: 100%;
        }
        #pos_batch_select_table td:before, .batch_details_modal table td:before, #batch_details_table td:before, #ledger_table td:before {
            content: attr(data-label);
            font-weight: 600;
            text-align: left;
            flex: 1;
            padding-right: 10px;
            color: #555;
        }
        /* Handle special elements inside td */
        #pos_batch_select_table td strong, .batch_details_modal table td strong, #batch_details_table td strong, #ledger_table td strong {
            display: inline-block;
        }
        /* Action buttons column */
        #pos_batch_select_table td:last-child, .batch_details_modal table td:last-child, #batch_details_table td:last-child, #ledger_table td:last-child {
            display: block;
            text-align: center;
            padding-top: 12px !important;
            margin-top: 8px;
            border-top: 1px solid #eee !important;
        }
        #pos_batch_select_table td:last-child:before, .batch_details_modal table td:last-child:before, #batch_details_table td:last-child:before, #ledger_table td:last-child:before {
            content: none !important;
        }


        .table-responsive {
            border: none !important;
            overflow-x: visible !important;
        }
        
        .btn-xs, .btn-sm {
            padding: 6px 12px !important;
            font-size: 13px !important;
        }
    }

    /* Robust Global Receipt Printing Fix */
    @media print {
        /* When printing a receipt, hide all page content by default */
        body.is-printing-receipt * {
            visibility: hidden;
        }

        /* Show the receipt section and its children */
        body.is-printing-receipt #receipt_section,
        body.is-printing-receipt #receipt_section * {
            visibility: visible !important;
        }

        /* Force display:none on major UI components to prevent blank space/pages */
        body.is-printing-receipt .main-header,
        body.is-printing-receipt .main-sidebar,
        body.is-printing-receipt .thetop > aside,
        body.is-printing-receipt #scrollable-container,
        body.is-printing-receipt .wrapper > header,
        body.is-printing-receipt .wrapper > aside,
        body.is-printing-receipt footer,
        body.is-printing-receipt .no-print,
        body.is-printing-receipt .scrolltop,
        body.is-printing-receipt .modal:not(.show) {
            display: none !important;
        }

        /* Position receipt at the absolute top-left for the print engine */
        body.is-printing-receipt #receipt_section {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
        }

        /* Reset main containers to ensure they don't restrict the receipt size */
        body.is-printing-receipt, 
        body.is-printing-receipt .wrapper,
        body.is-printing-receipt main,
        body.is-printing-receipt .thetop,
        body.is-printing-receipt .content-wrapper {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            background-color: white !important;
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>


@if(!empty($__system_settings['additional_css']))
    {!! $__system_settings['additional_css'] !!}
@endif


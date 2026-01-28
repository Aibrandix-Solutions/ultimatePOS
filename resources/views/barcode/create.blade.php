@extends('layouts.app')
@section('title',  __('barcode.add_barcode_setting'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('barcode.add_barcode_setting')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
{!! \Collective\Html\FormFacade::open(['url' => action([\App\Http\Controllers\BarcodeController::class, 'store']), 'method' => 'post', 
'id' => 'add_barcode_settings_form' ]) !!}
  @component('components.widget')
  <div class="row">
    <div class="col-sm-12">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('name', __('barcode.setting_name') . ':*') !!}
          {!! \Collective\Html\FormFacade::text('name', null, ['class' => 'form-control', 'required',
          'placeholder' => __('barcode.setting_name')]) !!}
      </div>
    </div>
    <div class="col-sm-12">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('description', __('barcode.setting_description') ) !!}
          {!! \Collective\Html\FormFacade::textarea('description', null, ['class' => 'form-control',
          'placeholder' => __('barcode.setting_description'), 'rows' => 3]) !!}
      </div>
    </div>
    <div class="col-sm-12">
      <div class="form-group">
        <div class="checkbox">
          <label>
            {!! \Collective\Html\FormFacade::checkbox('is_continuous', 1, false, ['id' => 'is_continuous']) !!} @lang('barcode.is_continuous')</label>
          </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
         {!! \Collective\Html\FormFacade::label('top_margin', __('barcode.top_margin') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
          </span>
          {!! \Collective\Html\FormFacade::number('top_margin', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.top_margin'), 'min' => 0, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('left_margin', __('barcode.left_margin') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
          </span>
          {!! \Collective\Html\FormFacade::number('left_margin', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.left_margin'), 'min' => 0, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('width', __('barcode.width') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-width" aria-hidden="true"></i>
          </span>
          {!! \Collective\Html\FormFacade::number('width', null, ['class' => 'form-control',
          'placeholder' => __('barcode.width'), 'min' => 0.1, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('height', __('barcode.height') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-height" aria-hidden="true"></i>
          </span>
          {!! \Collective\Html\FormFacade::number('height', null, ['class' => 'form-control',
          'placeholder' => __('barcode.height'), 'min' => 0.1, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('paper_width', __('barcode.paper_width') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-width" aria-hidden="true"></i>
          </span>
          {!! \Collective\Html\FormFacade::number('paper_width', null, ['class' => 'form-control',
          'placeholder' => __('barcode.paper_width'), 'min' => 0.1, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6 paper_height_div">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('paper_height', __('barcode.paper_height') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-height" aria-hidden="true"></i>
          </span>
          {!! \Collective\Html\FormFacade::number('paper_height', null, ['class' => 'form-control',
          'placeholder' => __('barcode.paper_height'), 'min' => 0.1, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('stickers_in_one_row', __('barcode.stickers_in_one_row') . ':*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
          </span>
          {!! \Collective\Html\FormFacade::number('stickers_in_one_row', null, ['class' => 'form-control',
          'placeholder' => __('barcode.stickers_in_one_row'), 'min' => 1, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('row_distance', __('barcode.row_distance') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
          </span>
          {!! \Collective\Html\FormFacade::number('row_distance', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.row_distance'), 'min' => 0, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('col_distance', __('barcode.col_distance') . ' ('. __('barcode.in_in') . '):*') !!}
         <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>
          </span>
          {!! \Collective\Html\FormFacade::number('col_distance', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.col_distance'), 'min' => 0, 'step' => 0.00001, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6 stickers_per_sheet_div">
      <div class="form-group">
        {!! \Collective\Html\FormFacade::label('stickers_in_one_sheet', __('barcode.stickers_in_one_sheet') . ':*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-th" aria-hidden="true"></i>
          </span>
          {!! \Collective\Html\FormFacade::number('stickers_in_one_sheet', null, ['class' => 'form-control',
          'placeholder' => __('barcode.stickers_in_one_sheet'), 'min' => 1, 'required']) !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        <div class="checkbox">
          <label>
            {!! \Collective\Html\FormFacade::checkbox('is_default', 1) !!} @lang('barcode.set_as_default')</label>
          </div>
      </div>
    </div>
    <div class="col-sm-12 text-center">
      <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-lg tw-text-white">@lang('messages.save')</button>
    </div>
  </div>
  @endcomponent
  {!! \Collective\Html\FormFacade::close() !!}
</section>
<!-- /.content -->
@endsection
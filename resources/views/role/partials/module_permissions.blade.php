 @if(count($module_permissions) > 0)
  @php
    $module_role_permissions = [];
    if(!empty($role_permissions)) {
      $module_role_permissions = $role_permissions;
    }
  @endphp
  @foreach($module_permissions as $key => $value)
  <hr>
  <div class="row check_group">
    <div class="col-md-3">
      <h4>{{$key}}</h4>
    </div>
    <div class="col-md-9">
      @foreach($value as $module_permission)
      @php
        if(empty($role_permissions) && $module_permission['default']) {
          $module_role_permissions[] = $module_permission['value'];
        }
      @endphp
      <div class="col-md-12">
        <div class="checkbox">
          <label>
            @if(!empty($module_permission['is_radio']))
              {!! Form::radio('radio_option[' . $module_permission['radio_input_name'] . ']', $module_permission['value'], in_array($module_permission['value'], $module_role_permissions), 
            [ 'class' => 'input-icheck']) !!} {{ $module_permission['label'] }}
            @else
            {!! Form::checkbox('permissions[]', $module_permission['value'], in_array($module_permission['value'], $module_role_permissions), 
            [ 'class' => 'input-icheck']) !!} {{ $module_permission['label'] }}
            @endif
          </label>
        </div>

        @if(isset($module_permission['end_group']) && $module_permission['end_group'])
        <hr>
        @endif
      </div>
      @endforeach
    </div>
  </div>
  @endforeach
  <hr>
  <div class="row check_group">
    <div class="col-md-3">
      <h4>{{ __('business.settings') }}</h4>
    </div>
    <div class="col-md-9">
      <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::checkbox('permissions[]', 'business_location.view', in_array('business_location.view', $module_role_permissions), ['class' => 'input-icheck']) !!}
            {{ __('business.business_locations') }} — {{ __('messages.view') }}
          </label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::checkbox('permissions[]', 'business_location.create', in_array('business_location.create', $module_role_permissions), ['class' => 'input-icheck']) !!}
            {{ __('business.business_locations') }} — {{ __('messages.add') }}
          </label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::checkbox('permissions[]', 'business_location.update', in_array('business_location.update', $module_role_permissions), ['class' => 'input-icheck']) !!}
            {{ __('business.business_locations') }} — {{ __('messages.edit') }}
          </label>
        </div>
      </div>
      <div class="col-md-12">
        <div class="checkbox">
          <label>
            {!! Form::checkbox('permissions[]', 'business_location.toggle', in_array('business_location.toggle', $module_role_permissions), ['class' => 'input-icheck']) !!}
            {{ __('business.business_locations') }} — {{ __('lang_v1.activate_location') }}/{{ __('lang_v1.deactivate_location') }}
          </label>
        </div>
      </div>
    </div>
  </div>
@endif
<div class="form-group">
    @if(! isset($no_label) || ! $no_label ) <label> Image*</label> @endif
    <div class="fileinput fileinput-new" data-provides="fileinput">
        <div class="fileinput-new thumbnail text-center"  style="max-height: 150px; width: 100%;">
		   	<a class="fancy-box" href="{{ isset($model) && $model->image ? $model->image : asset('img/no-image.png') }}">
			    <img src="{{ isset($model) && $model->image ? $model->image : asset('img/no-image.png') }}" 
			    	class="img-thumnail rounded" style="max-height: 150px;" 
			    	onerror="this.onerror=null;this.src='{{ asset('img/no-image.png') }}'">
		   	</a>
        </div>
        <div class="fileinput-preview fileinput-exists thumbnail text-center" style="max-height: 150px; width: 100%;" ></div>
        <div class="text-center image-actions">
            <span class="btn btn-primary btn-flat btn-file text-center">
                <span class="fileinput-new">Select Image</span>
                <span class="fileinput-exists">Change</span>
                <input type="file" name="@if(isset($name)){{ $name }}@else{{ 'image' }}@endif" class="file_input">
            </span>
            <a name="img_clear" class="btn btn-flat btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i></a>
			@if((isset($required) && ! $required) || ! isset($required)) 
				{{ Form::hidden(isset($image_remove_name) ? $image_remove_name : 'image_remove', false, ['class' => 'file_remove_input']) }}
        		<a name="img_remove" class="btn btn-flat btn-danger img-remove fileinput-new" data-dismiss="fileinput"><i class="fa fa-trash"></i></a>
            @endif
        </div>
    </div>
</div>
@include('base::partials.fancybox')
@push('after-styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/jasny-bootstrap.min.css') }}">
	<style type="text/css">
		.fileinput-new.thumbnail.text-center,
		.fileinput-new.thumbnail, .thumbnail.fileinput-exists, .fileinput{
			max-height: 200px;
			width: 100%;
			height: 100%;
		}
	</style>
@endpush
@push('after-scripts')
	<script type="text/javascript" src="{{ asset('js/plugins/jasny-bootstrap.min.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.image-actions').on('click', '[name=img_remove]', function () {
				$('.thumbnail img').attr("src", "{{ asset('img/no-image.png')}}");
			})
			$('.file_input').on('change', function () {
				 $('.file_remove_input').val($(this).val() == '')
			})
			$('[name=img_remove]').click(function(){
				 $('.thumbnail img').attr("src", "{{ asset('img/no-image.png')}}");
				 $('.file_remove_input').val(true)
			});
		});
	</script>
@endpush
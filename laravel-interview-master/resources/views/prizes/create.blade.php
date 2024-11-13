@extends('default')

@section('content')

@include('prob-notice')

                <!-- Show the alert only when remainingProbability is greater than 0 -->
                @if($remainingProbability > 0)
                    <div class="alert alert-info">
                        <strong>Sum of all prizes probability must be 100%. Currently it's {{ $totalProbability }}%. You have yet to add {{ $remainingProbability }}% to the prize.</strong>
                    </div>
                @endif


	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif

	{!! Form::open(['route' => 'prizes.store']) !!}

		<div class="mb-3">
			{{ Form::label('title', 'Title', ['class'=>'form-label']) }}
			{{ Form::text('title', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
    {{ Form::label('probability', 'Probability', ['class'=>'form-label']) }}
    {{ Form::number('probability', null, ['class' => 'form-control', 'min' => '0', 'max' => '100', 'placeholder' => '0 - 100', 'step' => '0.01', 'id' => 'probability']) }}
    <small id="remaining-probability" class="form-text text-muted"></small> <!-- This is where remaining probability will be shown -->
</div>



		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
	
	@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const form = document.querySelector('form');
			form.addEventListener('submit', function (event) {
				let totalProbability = 0;

				// Add all prize probabilities
				const probabilities = document.querySelectorAll('input[name="probability"]');
				probabilities.forEach(input => {
					totalProbability += parseFloat(input.value) || 0;
				});

				// Check if total exceeds 100
				if (totalProbability > 100) {
					event.preventDefault();  // Prevent form submission
					alert("Total probability cannot exceed 100.");
				}
			});

		});
	</script>
	@endpush



@stop

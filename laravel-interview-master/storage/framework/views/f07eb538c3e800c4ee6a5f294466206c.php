<?php $__env->startSection('content'); ?>

<?php echo $__env->make('prob-notice', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- Show the alert only when remainingProbability is greater than 0 -->
                <?php if($remainingProbability > 0): ?>
                    <div class="alert alert-info">
                        <strong>Sum of all prizes probability must be 100%. Currently it's <?php echo e($totalProbability); ?>%. You have yet to add <?php echo e($remainingProbability); ?>% to the prize.</strong>
                    </div>
                <?php endif; ?>



	<?php if($errors->any()): ?>
		<div class="alert alert-danger">
			<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php echo e($error); ?> <br>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	<?php endif; ?>

	<?php echo e(Form::model($prize, array('route' => array('prizes.update', $prize->id), 'method' => 'PUT'))); ?>


		<div class="mb-3">
			<?php echo e(Form::label('title', 'Title', ['class'=>'form-label'])); ?>

			<?php echo e(Form::text('title', null, array('class' => 'form-control'))); ?>

		</div>
		<div class="mb-3">
    <?php echo e(Form::label('probability', 'Probability', ['class'=>'form-label'])); ?>

    <?php echo e(Form::number('probability', null, ['class' => 'form-control', 'min' => '0', 'max' => '100', 'placeholder' => '0 - 100', 'step' => '0.01', 'id' => 'probability'])); ?>

    <small id="remaining-probability" class="form-text text-muted"></small> <!-- This is where remaining probability will be shown -->
</div>

		<?php echo e(Form::submit('Edit', array('class' => 'btn btn-primary'))); ?>


	<?php echo e(Form::close()); ?>

	<?php $__env->startPush('scripts'); ?>
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
	<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Lenovo\Documents\PHP_project\laravel-interview-master\resources\views/prizes/edit.blade.php ENDPATH**/ ?>
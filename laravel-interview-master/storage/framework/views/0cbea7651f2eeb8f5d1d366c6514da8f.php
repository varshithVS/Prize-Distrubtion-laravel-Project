<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('prob-notice', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="container">
        <div class="row">
                <!-- Show the alert only when remainingProbability is greater than 0 -->
                <?php if($remainingProbability > 0): ?>
                    <div class="alert alert-info">
                        <strong>Sum of all prizes probability must be 100%. Currently it's <?php echo e($totalProbability); ?>%. You have yet to add <?php echo e($remainingProbability); ?>% to the prize.</strong>
                    </div>
                <?php endif; ?>
            <hr>
            <div class="col-md-12">
                <div class="d-flex justify-content-end mb-3">
                    <a href="<?php echo e(route('prizes.create')); ?>" class="btn btn-info">Create</a>
                </div>
                <h1>Prizes</h1>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Probability</th>
                            <th>Awarded</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $prizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prize): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr id="prize-<?php echo e($prize->id); ?>">
                                <td><?php echo e($prize->id); ?></td>
                                <td><?php echo e($prize->title); ?></td>
                                <td><?php echo e($prize->probability); ?></td>
                                <td class="award-column" data-prize-id="<?php echo e($prize->id); ?>">
                                    <!-- Display the number of times the prize has been awarded -->
                                    <?php echo e(session('distributedPrizes')[$prize->id]['awarded'] ?? 0); ?>

                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo e(route('prizes.edit', [$prize->id])); ?>" class="btn btn-primary">Edit</a>
                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['prizes.destroy', $prize->id]]); ?>

                                        <?php echo Form::submit('Delete', ['class' => 'btn btn-danger']); ?>

                                        <?php echo Form::close(); ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3>Simulate</h3>
                    </div>
                    <div class="card-body">
                        <?php echo Form::open(['method' => 'POST', 'route' => ['simulate']]); ?>

                        <div class="form-group">
                            <?php echo Form::label('number_of_prizes', 'Number of Prizes'); ?>

                            <?php echo Form::number('number_of_prizes', old('number_of_prizes', $prize->number_of_prizes ?? null), ['class' => 'form-control']); ?>

                        </div>
                        <?php echo Form::submit('Simulate', ['class' => 'btn btn-primary']); ?>

                        <?php echo Form::close(); ?>

                    </div>
                    <br>
                    <div class="card-body">
                        <?php echo Form::open(['method' => 'POST', 'route' => ['reset']]); ?>

                        <?php echo Form::submit('Reset', ['class' => 'btn btn-primary']); ?>

                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="container mb-4">
    <div class="row">
        <div class="col-md-6">
            <h2>Probability Settings</h2>
            <!-- Set a fixed size for the canvas -->
            <div style="position: relative; height: 500px; width: 600px;">
                <canvas id="probabilityChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <h2>Actual Rewards</h2>
            <div style="position: relative; height: 500px; width: 600px;">
                <canvas id="awardedChart" style="display: none;"></canvas> <!-- Hide initially -->
            </div>
        </div>
    </div>
</div>

    <script>
    // Pass PHP data to JavaScript
    const prizeTitles = <?php echo json_encode($prizeTitles, 15, 512) ?>;
    const probabilities = <?php echo json_encode($probabilities, 15, 512) ?>;
    const distributedPrizes = <?php echo json_encode(session('distributedPrizes', []), 512) ?>; // Get the simulated prize data

    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        // Probability Chart (unchanged)
        if (probabilities.length > 0) {
            const ctx = document.getElementById('probabilityChart').getContext('2d');
            const probabilityChart = new Chart(ctx, {
                type: 'doughnut',  // Use 'doughnut' type to create a gap in the middle
                data: {
                    labels: prizeTitles, // Prize titles as labels
                    datasets: [{
                        data: probabilities, // Probability values
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            formatter: (value) => value + '%',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    title: {
                        display: true,
                        text: 'Prize Probability Distribution'
                    }
                }
            });
        } else {
            console.log("No probability data available to display.");
        }

        // Actual Rewards Chart (new implementation)
        if (Object.keys(distributedPrizes).length > 0) {
            const prizeTitlesActual = Object.values(distributedPrizes).map(prize => prize.title);
            const awardedValues = Object.values(distributedPrizes).map(prize => prize.awarded);

            const ctx2 = document.getElementById('awardedChart').getContext('2d');
            const awardedChart = new Chart(ctx2, {
            type: 'doughnut',  // Use 'doughnut' type to create a gap in the middle
            data: {
                labels: prizeTitlesActual, // Prize titles from the simulation
                datasets: [{
                    data: awardedValues, // Number of awards each prize got
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 50, // Adds a gap in the middle of the pie chart (50% is a large gap)
                plugins: {
                    datalabels: {
                        color: '#fff', // Text color
                        formatter: (value, ctx) => {
                            let label = ctx.chart.data.labels[ctx.dataIndex]; // Get label from the data
                            return label + ': ' + value; // Show label and value
                        },
                        font: {
                            weight: 'bold',
                            size: 16
                        },
                        align: 'center', // Align text in the center
                        anchor: 'center' // Anchor text in the center of each slice
                    }
                },
                tooltips: {
                    enabled: false // Disable the tooltip hover effect, as we want the values to be displayed all the time
                },
                title: {
                    display: true,
                    text: 'Actual Prize Distribution'
                }
            }
        });

            // Show the chart after simulation
            document.getElementById('awardedChart').style.display = 'block';
        }

        // Reset Button Click Handler: Clear the chart and hide it
        document.querySelector('form[action="<?php echo e(route('reset')); ?>"]').addEventListener('submit', function() {
            const chartCanvas = document.getElementById('awardedChart');
            chartCanvas.style.display = 'none'; // Hide chart when resetting
            chartCanvas.getContext('2d').clearRect(0, 0, chartCanvas.width, chartCanvas.height); // Clear the chart canvas
        });

         // Update the Awarded column in the table based on the simulation result
        if (Object.keys(distributedPrizes).length > 0) {
            Object.keys(distributedPrizes).forEach(prizeId => {
                const awardedValue = distributedPrizes[prizeId].awarded;
                const awardColumn = document.querySelector(`#prize-${prizeId} .award-column`);
                if (awardColumn) {
                    awardColumn.textContent = awardedValue; // Update the awarded value
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Lenovo\Documents\PHP_project\laravel-interview-master\resources\views/prizes/index.blade.php ENDPATH**/ ?>
<?php
/**
 * @package GymBuilder
 * @var array $args
 * @var array   $schedule
 * @var array $weeknames
 * @var array $class_query_info
 * @version 1.0.0
 */
use GymBuilder\Inc\Controllers\Models\GymBuilderClass;
?>
<div class="gym-builder-table-routine">
    <table>
        <tr>
            <th></th>
			<?php foreach ( $weeknames as $weekname ): ?>
                <th class="column-title"><span><?php echo esc_html( $weekname );?></span></th>
			<?php endforeach; ?>
        </tr>
		<?php foreach ( $schedule as $schedule_time => $schedule_value ): ?>
            <tr>
                <th class="column-title"><?php echo $schedule_time;?></th>
				<?php
				foreach ( $weeknames as $weekname => $weekvalue ) {
					$has_cell = false;
					foreach ( $schedule_value as $schedule_week => $routine ) {
						if ( $weekname == $schedule_week ) {
							echo '<td>';
							GymBuilderClass::print_routine( $routine );
							echo '</td>';
							$has_cell = true;
						}
					}
					if ( !$has_cell ) {
						echo '<td></td>';
					}
				}
				?>
            </tr>
		<?php endforeach; ?>
    </table>
</div>






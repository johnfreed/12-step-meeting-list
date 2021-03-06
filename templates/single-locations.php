<?php 
tsml_assets();
get_header(); 
$location = tsml_get_location();
?>
<div id="tsml">
	<div id="location" class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1 main">
			
				<div class="page-header">
					<h1><?php echo $location->post_title?></h1>
					<?php echo tsml_link(get_post_type_archive_link('tsml_meeting'), '<i class="glyphicon glyphicon-chevron-right"></i> ' . __('Back to Meetings', '12-step-meeting-list'), 'tsml_location')?>
				</div>
	
				<div class="row location">
					<div class="col-md-4">
						<div class="panel panel-default">
							<ul class="list-group">
								<a href="<?php echo $location->directions?>" class="list-group-item">
									<?php echo tsml_format_address($location->formatted_address)?>
								</a>
	
								<?php if ($location->region) {?>
									<li class="list-group-item"><?php echo $location->region?></li>
								<?php }
									
								if (!empty($location->notes)) {?>
									<li class="list-group-item"><?php echo $location->notes?></li>
								<?php }
								
								$meetings = tsml_get_meetings(array('location_id'=>$location->ID));
								$location_days = array();
								foreach ($meetings as $meeting) {
									if (!isset($location_days[$meeting['day']])) $location_days[$meeting['day']] = array();
									$location_days[$meeting['day']][] = '<li><span>' . $meeting['time_formatted'] . '</span> ' . tsml_link($meeting['url'], tsml_format_name($meeting['name'], $meeting['types']), 'tsml_location') . '</li>';
								}
								ksort($location_days);
								if (count($location_days)) {?>
								<li class="list-group-item">						
								<?php foreach ($location_days as $day=>$meetings) {?>
									<h4><?php if (!empty($tsml_days[$day])) echo $tsml_days[$day]?></h4>
									<ul class="meetings"><?php echo implode($meetings)?></ul>
								<?php }?>
								</li>
								<?php }?>
	
								<li class="list-group-item">
									<?php _e('Updated', '12-step-meeting-list')?>
									<?php the_modified_date()?>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-md-8">
						<div id="map" class="panel panel-default"></div>
						<script>
							var map;
	
							google.maps.event.addDomListener(window, 'load', function() {
								map = new google.maps.Map(document.getElementById('map'), {
									zoom: 15,
									panControl: false,
									mapTypeControl: false,
									zoomControlOptions: { style: google.maps.ZoomControlStyle.SMALL },
									center: new google.maps.LatLng(<?php echo $location->latitude + .0025 . ',' . $location->longitude?>),
									mapTypeId: google.maps.MapTypeId.ROADMAP
								});
	
								var contentString = '<div class="infowindow">'+
									'<h3><?php esc_attr_e($location->post_title, '12-step-meeting-list')?></h3>'+
									'<p><?php echo tsml_format_address($location->formatted_address)?></p>'+
									'<p><a class="btn btn-default" href="<?php echo $location->directions?>" target="_blank"><?php _e('Directions', '12-step-meeting-list')?></a></p>' +
									'</div>';
	
								var infowindow = new google.maps.InfoWindow({
									content: contentString
								});
	
								var marker = new google.maps.Marker({
									position: new google.maps.LatLng(<?php echo $location->latitude . ',' . $location->longitude?>),
									map: map,
									title: '<?php the_title(); ?>'
								});
	
								infowindow.open(map,marker);
	
								google.maps.event.addListener(marker, 'click', function() {
									infowindow.open(map,marker);
								});
							});
						</script>
					</div>
				</div>
			
			</div>
		</div>
	</div>
</div>
<?php
get_footer();

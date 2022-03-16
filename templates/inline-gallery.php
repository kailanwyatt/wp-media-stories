<div class="wpms--inline-image-container">
	<div class="wpms--inline-image-block">
		<div class="wpms--inline-image" style="background-image: url('<?php echo esc_url( $main_image ); ?>');">
			<img src="<?php echo esc_url( $main_image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
		</div>
		<div class="wpms--inline-image-overlay"></div>
		<div class="wpms--inline-image-caption"><a href="#" class="wpms--inline-image-link" data-title="<?php echo esc_attr( $title ); ?>" data-media_id="<?php echo absint( $media_id ) ?>"><?php echo esc_html( $title ); ?></a></div>
	</div>
</div>
<script type="text/javascript">
	if (typeof wp_media_stories == "undefined") {
		var wp_media_stories = [];
	}
	wp_media_stories[<?php echo absint( $media_id ); ?>] = <?php echo json_encode( $args ); ?>;
</script>
<script type="type="text/x-handlebars-template" id="um_gallery_media">
	<div id="um-gallery-modal" class="popup wpms--popup" data-id="{{media_id}}">
		<a href="" class="mfp-close">&#215;</a>
		<div class="main-content">
			<h1>{{album_title}}</h1>
			<div class="gallery-image">
				<ul class="slides">
					{{#each media_items}}
			        <li>
			          <img src="{{full_url}}" class="wpms--popup-image" alt="{{title}}">
			          <p class="caption">{{position}} OF {{total_media}}</p>
			        </li>
			        {{/each}}
			     </ul>
		     </div>
		</div>
		<div class="side-content">
			<div class="slide-content-rows">
				{{#each media_items}}
				<div>
					<h2>{{title}}</h2>
					<p>{{{caption}}}</p>
				</div>
				{{/each}}
			</div>
		</div>
	</div>
</script>

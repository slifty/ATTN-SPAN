(function( $ ) {
	var DATA_CLIPS = "clips";
	var DATA_CURRENT_CLIP = "current";
	
	var CLASS_PLAYER = "attn-player";
	var CLASS_VIDEO_CONTAINER = "attn-video-container";
	var CLASS_VIDEO_CLIP = "attn-clip";
	var CLASS_TIMELINE = "attn-timeline";
	
	var CLASS_INFORMATION = "attn-information";
	var CLASS_INFORMATION_TITLE = "attn-information-title";
	var CLASS_INFORMATION_DESCRIPTION = "attn-information-description";
	var CLASS_INFORMATION_CONTEXT = "attn-information-context";
	
	var methods = {
		init: function() {
			this.addClass(CLASS_PLAYER);
			
			// Video Container
			$videoContainer = $("<div />");
			$videoContainer.addClass(CLASS_VIDEO_CONTAINER);
			
			// Timeline
			$timeline = $("<div />");
			$timeline.addClass(CLASS_TIMELINE);
			
			// Details
			$information = $("<div />");
			$information.addClass(CLASS_INFORMATION);
			$title = $("<div />");
			$title.addClass(CLASS_INFORMATION_TITLE);
			$description = $("<div />");
			$description.addClass(CLASS_INFORMATION_DESCRIPTION);
			$context = $("<div />");
			$context.addClass(CLASS_INFORMATION_CONTEXT);
			
			$information.append($title);
			$information.append($description);
			$information.append($context);
			
			// Append them all
			this.append($videoContainer);
			this.append($timeline);
			this.append($information);
			
			return this;
		},
		
		load: function(clipList) {
			this.data(DATA_CLIPS, clipList);
			this.data(DATA_CURRENT_CLIP, 0);
			var attnPlayer = this;
			var videoPlayer = attnPlayer.children("." + CLASS_VIDEO_CONTAINER);
			
			// Begin loading the videos
			var loadClip = function(clipID) {
				// Does this clip actually exist?
				if(clipList[clipID] == undefined)
					return;
				
				// Has this clip been loaded already?
				if($("#clipDiv" + clipID).length > 0)
					return;
				
				var clip = clipList[clipID];
				$videoEl = $("<video controls/>");
				$videoEl.attr("id", "clip" + clipID);
				
				$videoEl.bind("play", function() {
					// Deactivate previously active video
					$("." + CLASS_VIDEO_CLIP + " .active").removeClass("active");
					
					// Activate this video
					$clipContainer = $(this).parent();
					$clipContainer.addClass("active");
					$clipContainer = $("#clipDiv" + (clipID + 1));
					
					// Update the information displays
					$title = attnPlayer.find("." + CLASS_INFORMATION_TITLE);
					$description = attnPlayer.find("." + CLASS_INFORMATION_DESCRIPTION);
					$context = attnPlayer.find("." + CLASS_INFORMATION_CONTEXT);
					
					$title.html(clip.title);
					$description.html(clip.description);
					$context.html("<a href='" + clip.contextURL + "' target='_blank'>Context</a>");
				});

				$videoEl.bind("canplaythrough", function() {
					var currentClip = attnPlayer.data(DATA_CURRENT_CLIP);
					
					if(clipID == currentClip)
						this.play();
					
					// Buffer the next clip
					loadClip(clipID + 1);
				});
				
				$videoEl.bind("ended", function() {
					// If this was the last clip, stop.
					if(clipList[clipID + 1] == undefined)
						return;
					
					// Hide this clip
					$clipContainer = $(this).parent();
					$clipContainer.removeClass("active");
					
					// Start the next clip
					attnPlayer.attnPlayer("playClip", clipID + 1);
				});
				
				$sourceEl = $("<source />");
				$sourceEl.attr("src", clip.feedURL);
				$sourceEl.attr("type", "video/ogg");
				$videoEl.append($sourceEl);
				
				$containerEl = $("<div />");
				$containerEl.attr("id", "clipDiv" + clipID);
				$containerEl.addClass(CLASS_VIDEO_CLIP);
				$containerEl.append($videoEl);
				videoPlayer.append($containerEl);
			};
			loadClip(0);
		},
		
		playClip: function(clipID) {
			var clipList = this.data(DATA_CLIPS);
			
			// Make sure we aren't out of index
			if(clipID >= clipList.length)
				return this;
			
			// Set the active clip
			this.data(DATA_CURRENT_CLIP, clipID);
			
			// Get the clip
			$clip = $("#clip" + clipID);
			
			// Start the clip
			$clip[0].play();
		}
	}

	$.fn.attnPlayer = function(method) {
		// Method calling logic
		if ( methods[method] ) {
			if ( method == "init" )
				return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));

			if ( !this.hasClass(CLASS_PLAYER) ) {
				$.error( "attnPlayer methods can only be run on attnPlayer elements" );
			}
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === "object" || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( "Method " +  method + " does not exist on jQuery.attnPlayer" );
		}    
	};
})( jQuery );
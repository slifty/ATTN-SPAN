(function( $ ) {
	var DATA_CLIPS = "clips";
	var DATA_CURRENT_CLIP = "current";
	
	var CLASS_PLAYER = "attn-player";
	var CLASS_VIDEO_CONTAINER = "attn-video-container";
	var CLASS_VIDEO_CLIP = "attn-clip";
	
	var CLASS_TIMELINE = "attn-timeline";
	var CLASS_TIMELINE_CLIP = "attn-timeline-clip";
	
	var CLASS_INFORMATION = "attn-information";
	var CLASS_INFORMATION_TITLE = "attn-information-title";
	var CLASS_INFORMATION_DESCRIPTION = "attn-information-description";
	var CLASS_INFORMATION_CONTEXT = "attn-information-context";
	
	var CLASS_SOCIAL = "attn-social";
	var CLASS_SOCIAL_GOOD = "attn-social-good";
	var CLASS_SOCIAL_BAD = "attn-social-bad";
	
	var FLAG_GOOD = "good";
	var FLAG_BAD = "bad";
	
	var API_FLAG_ADD = "api/flag_add.php";
	
	var goodCount = 0;
	var badCount = 0;
	
	var methods = {
		init: function() {
			var attnPlayer = this;
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
			
			// Social Layer
			$social = $("<div />");
			$social.addClass(CLASS_SOCIAL);
			
			$good = $("<div />");
			$good.html("Yay!");
			$good.bind("click", function() {
				attnPlayer.attnPlayer("flagMoment", FLAG_GOOD);
			});
			$good.addClass(CLASS_SOCIAL_GOOD);
			
			$bad = $("<div />");
			$bad.html("Boo!");
			$bad.bind("click", function() {
				attnPlayer.attnPlayer("flagMoment", FLAG_BAD);
			});
			$bad.addClass(CLASS_SOCIAL_BAD);
			
			$social.append($good);
			$social.append($bad);
			
			// Append them all
			this.append($videoContainer);
			this.append($information);
			this.append($social);
			this.append($timeline);
			
			return this;
		},
		
		flagMoment: function(flagType) {
			var clipList = this.data(DATA_CLIPS);
			var currentClip = this.data(DATA_CURRENT_CLIP);
			var $clip = $("#clip" + currentClip);
			var timeOffset = $clip[0].currentTime;
			
			var flagData = {
				type: flagType,
				clipID: clipList[currentClip].clipID,
				time: timeOffset
			};
			
			$.ajax(API_FLAG_ADD, {
				data: flagData,
				type: "POST"
			});
		},
		
		load: function(clipList) {
			this.data(DATA_CLIPS, clipList);
			this.data(DATA_CURRENT_CLIP, 0);
			var attnPlayer = this;
			var videoPlayer = attnPlayer.children("." + CLASS_VIDEO_CONTAINER);
			var timeline = attnPlayer.children("." + CLASS_TIMELINE);
			
			// Set up the timeline
			for(var x in clipList) {
				$navDiv = $("<div />");
				$navDiv.attr("id", "navClip" + x);
				$navDiv.addClass(CLASS_TIMELINE_CLIP);
				
				(function(clipID) { $navDiv.bind("click", function() {
					attnPlayer.attnPlayer("playClip",clipID);
				})})(parseInt(x));
				
				// Thumbnail
				$thumb = $("<img />");
				$thumb.attr("src", clipList[x].thumbnail);
				$navDiv.append($thumb);
				
				timeline.append($navDiv);
			}
			
			// Start loading the first clip
			this.attnPlayer("playClip",0);
		},
		
		loadClip: function(clipID) {
			var clipList = this.data(DATA_CLIPS);
			var attnPlayer = this;
			var videoPlayer = attnPlayer.children("." + CLASS_VIDEO_CONTAINER);
			
			// Does this clip actually exist?
			if(clipList[clipID] == undefined)
				return;
			
			// Has this clip been loaded already?
			if($("#clipDiv" + clipID).length > 0)
				return;
			
			var clip = clipList[clipID];
			$videoEl = $("<video controls preload />");
			$videoEl.attr("id", "clip" + clipID);
			
			var $goodDiv = attnPlayer.find("." + CLASS_SOCIAL_GOOD);
			var $badDiv = attnPlayer.find("." + CLASS_SOCIAL_BAD);
			var cursor = 0;
			$videoEl.bind("timeupdate", function() {
				var floorTime = Math.floor(this.currentTime);
				if(floorTime != cursor) {
					cursor = floorTime;
					var flags = clipList[clipID].flags;
					for(x in flags) {
						flag = flags[x];
						if(flag.time == (cursor + parseInt(clipList[clipID].start))) {
							if(flag.type == FLAG_GOOD) {
								goodCount++;
							}
							else if(flag.type == FLAG_BAD) {
								badCount++;
							}
						}
					}
					
					$goodDiv.animate({boxShadow: '0px 0px '+ ( goodCount * 2 ) + 'px #FFFF00'},  {duration: 1000, queue: false});
					$badDiv.animate({boxShadow: '0px 0px ' + ( badCount * 2 ) + 'px #FFFF00'},  {duration: 1000, queue: false});

					goodCount = Math.max(0, goodCount - 3);
					badCount = Math.max(0, badCount - 3);
					//badCount = Math.floor(badCount / 2.0);

				}
			});
			
			$videoEl.bind("play", function() {
				// Pause other videoss
				var videos = $("video");
				for(var x = 0; x < videos.length; ++x) {
					var videoID = parseInt($(videos[x]).attr("id").slice(4)); // "clip" has 4 characters;
					if(videoID != clipID)
						videos[x].pause();
				}
				
				// Deactivate previously active video
				$("." + CLASS_VIDEO_CLIP + ".active").removeClass("active");
				$("." + CLASS_TIMELINE_CLIP + ".active").removeClass("active");
				
				// Activate this video
				$clipContainer = $(this).parent();
				$clipContainer.addClass("active");
				
				// Activate the nav
				$clipThumb = $(attnPlayer).find("#navClip" + clipID);
				$clipThumb.addClass("active");
				
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
				$clipThumb = $(attnPlayer).find("#navClip" + clipID);
				$clipThumb.addClass("loaded");
				
				if(clipID == currentClip)
					this.play();
				
				// Buffer the next clip
				attnPlayer.attnPlayer("loadClip", (clipID + 1));
			});
			
			$videoEl.bind("ended", function() {
				// If this was the last clip, stop.
				if(clipList[clipID + 1] == undefined)
					return;
				
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
			
			// Start the loading process
			$clip = $("#clip" + clipID);
			$clip[0].load();
		},
		
		playClip: function(clipID) {
			var clipList = this.data(DATA_CLIPS);
			
			// Make sure we aren't out of index
			if(clipID >= clipList.length)
				return this;
			
			// Set the active clip
			this.data(DATA_CURRENT_CLIP, clipID);
			
			// Is this clip loaded?
			$clip = $("#clip" + clipID);
			if($clip.length == 0) {
				this.attnPlayer("loadClip", clipID);
				$clip = $("#clip" + clipID);
			}
			
			// Start the clip
			$clip[0].play();
			if($clip[0].currentTime)
			 	$clip[0].currentTime= 0;
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
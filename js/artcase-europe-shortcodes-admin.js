(function($) {

	$(document).ready(function(){
    	resize();
    	$(window).on("resize", function(){                      
        	resize();
    	});
  	});

  	function resize(){    
    	$(".device-mockup").outerHeight($(".device-mockup").outerWidth());
  	}
	
	function readURL(input) {
	  if (input.files && input.files[0]) {
	    var reader = new FileReader();
	    
	    reader.onload = function(e) {
	      $('#device-design-img').attr('src', e.target.result);
	    }
	    
	    reader.readAsDataURL(input.files[0]);
	  }
	}

	function generateRenders(){
		var renderCounter = 0;
		$('.loading-renders').text("Generating Renders...");
		$('.device-mockup').each(function(i, obj) {
    		var c=document.getElementById(obj.id);
			var ctx=c.getContext("2d");
			var imageBackground = new Image();
			var imageOver = new Image();
			imageBackground.crossOrigin = imageOver.crossOrigin = 'Anonymous';
			imageBackground.src = document.getElementById("device-design-img").src;
			imageBackground.onload = function() {
				let standardSize = c.width = c.height = 720;
				let landScape = (imageBackground.naturalWidth >= imageBackground.naturalHeight);
				let scaleFactor = landScape ? (imageBackground.naturalWidth / 720) : (imageBackground.naturalHeight / 720);
				imageOver.src = obj.dataset.imgSrc;
				let imgName = obj.dataset.imgName;
				imgName = imgName.split(" ");
				let deviceSide = imgName[imgName.length - 1];
				if (deviceSide == 'Left') {
					var offSet = -240;
				} else if(deviceSide == 'Right'){
					var offSet = 240;
				} else {
					var offSet = 0;
				}
				let x = 0;
				let y = 0;
				if (landScape){
					y = (720 - (imageBackground.naturalHeight / scaleFactor))/2;
				} else {
					x = (720 - (imageBackground.naturalWidth / scaleFactor))/2;
				}
			   	ctx.drawImage(imageBackground, (x + offSet), y, (imageBackground.naturalWidth / scaleFactor), (imageBackground.naturalHeight / scaleFactor));
			   	imageOver.onload = function() {
			      	ctx.drawImage(imageOver, 0, 0, standardSize, standardSize);			      	
					$(obj).fadeIn('fast');
					resize();
					renderCounter++;
					if (renderCounter === $('.single-mockup-container').length){
						$('.loading-renders').text("All Renders Generated Successfully");
					}
			   }
			};
			})
		
	}

	$("#device-design-input").change(function() {
	  	readURL(this);
	});	

	$("#generate-renders").click(function() {
		generateRenders();
	});

	$("#device-design-img").click(function() {
	  	$("#device-design-input").click();
	});	

	$("#upload-renders").click(function() {
		let caseTitle = $("#product_name_es").val();
		if (caseTitle == '') {
			alert('Debes escribir primero el nombre de la Funda');
			return false;
		}
		var counter = 0;
		$('.loading-renders').text("Uploading Renders...");
		$(".single-device-images").each(function(i, obj) {			
			$(obj).find('canvas').each(function(j, object) {
				var auxId = object.id;
				console.log(auxId);
				let data = object.toDataURL('image/jpeg', 0.85);				
				let imageTitle = caseTitle + ' ' +object.dataset.imgName;
				$.ajax({
					type: "POST",
					url: 'https://www.solidmood.com/wp-admin/admin-ajax.php',
					data: {
						action: 'canvasUpload',
						uploadImage: data,
						title: imageTitle

					}
				}).done(function(o) {	
					counter++;				
					$('#'+object.id+'-input').val(o/10);
					$('#'+object.id+'-input')[0].insertAdjacentHTML("afterend", "Uploaded attachment with id: " + (o/10));
					if (counter === $('.single-mockup-container').length){
						$('.loading-renders').text("All Renders Uploaded Successfully");
					}					
				});
			});
		});			
	});


})( jQuery );
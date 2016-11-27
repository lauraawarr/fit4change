window.addEventListener('load', init);

function init(){

	var links = document.querySelectorAll('a');

	var hash;

	for (var i = 0; i < links.length; i++){
		links[i].addEventListener('click', function(ev){
			//checks if anchor is on current page and hash value is present
			if ((ev.target.pathname === window.location.pathname)&&(ev.target.hash != "")){ 
				//stops default behaviour
				ev.preventDefault();

				//store hash
				hash = ev.target.hash;
				console.log(hash)

				$('html, body').animate({
			        scrollTop: $(hash).offset().top
			      	}, 800, function(){
					//adds hash to browser location
						window.location.hash = hash;
					});
			}; //end else/if
		}); //end event listener
	}; //end loop over links
}; //end init

var tif = document.querySelector('#tif');
var motif = document.querySelector('#motif');

motif.addEventListener('mouseover', flip);
motif.addEventListener('mouseleave', flip);
motif.addEventListener('click', flip);
var active = false;

function flip(){
	if (active){
		tif.classList.remove('flipIn');
		tif.classList.add('flipOut');
		console.log('off');
		active = false;
	} else {
		tif.classList.remove('flipOut');
		tif.classList.add('flipIn');
		console.log('on');
		active = true;
	}
};
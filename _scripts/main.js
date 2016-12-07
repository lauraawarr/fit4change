window.addEventListener('load', init);

function init(){

	var links = document.querySelectorAll('a');

	var hash;

	if (window.location.hash == "#post-charity-results") {
		smoothScroll('#results');
	} else {
		console.log(window.location.hash)
	}

	for (var i = 0; i < links.length; i++){
		links[i].addEventListener('click', function(ev){
			//checks if anchor is on current page and hash value is present
			if ((ev.target.pathname === window.location.pathname)&&(ev.target.hash != "")){ 

				//stops default behaviour
				ev.preventDefault();

				//store hash
				hash = ev.target.hash;

				smoothScroll(hash);

			}; //end else/if
		}); //end event listener
	}; //end loop over links

	//Make selected
	// .goal-type
	// button .category
	// .charity .header



}; //end init

function smoothScroll(hash){

	$('html, body').animate({
    scrollTop: $(hash).offset().top
  	}, 800, function(){
	//adds hash to browser location
		window.location.hash = hash;
	});
}; // end smooth scroll

var tif = document.querySelector('#tif');

setInterval(flip, 5000);
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
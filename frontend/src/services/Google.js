export const loadedGoogleMapsAPI = new Promise((resolve) => {

	window['GoogleMapsInit'] = resolve;

	let GMap = document.createElement('script');

	GMap.setAttribute('async', true);
	GMap.setAttribute('src',`https://maps.googleapis.com/maps/api/js?key=${process.env.VUE_APP_GOOGLE_MAPS}&libraries=places&callback=GoogleMapsInit`);

		//`https://maps.googleapis.com/maps/api/js?key=${process.env.VUE_APP_GOOGLE_MAPS}&libraries=places&callback=GoogleMapsInit&region=IN`);

	document.body.appendChild(GMap);
});
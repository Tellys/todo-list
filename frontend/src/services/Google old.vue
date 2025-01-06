<template>
	<div class="columns is-multiline">

		<div class="column is-full has-margin-sides-bottom">
			<div>
				<gmap-autocomplete id="location" class="input is-pulled-left" placeholder="Address or ZIP"
					@place_changed="setPlace" />
			</div>
			<div class="select">
				<select v-model="distance" class="is-rounded" name="distance">
					<option value="5">5 Miles</option>
					<option value="10">10 Miles</option>
					<option value="25">25 Miles</option>
					<option value="50">50 Miles</option>
					<option value="75">75 Miles</option>
					<option value="100">100 Miles</option>
				</select>
			</div>
			<a class="button is-primary">Search</a>
		</div>

		<div class="column is-full">

			center {{ center }}
			zoom {{ zoom }}
			markers {{ markers }}

		</div>
	</div>
</template>

<script>
/* global google */
import axios from 'axios'

export default {
	data: function () {
		return {
			center: { lat: 34.070321, lng: -84.274133 },
			zoom: 11,
			userPosition: null,
			markers: [],
			mapLoaded: false,
			// API Variables
			distance: 25,
			results: 25,
			page: 1,
			place: null
		}
	},

	watch: {
		distance: function (n) {
			this.getMarkers()
		},
		markers: function (newMarkers, o) {
			// Ensure we have markers in the list so that
			if (newMarkers.data.length > 2) {
				const bounds = new google.maps.LatLngBounds()
				for (let m of newMarkers.data) {
					bounds.extend({ lat: m.lat, lng: m.lng })
				}
				this.$refs.map.fitBounds(bounds)
			}
		}
	},

	mounted: function () {
		this.getMarkers()
	},

	methods: {
		getMarkers: function () {
			// Only after Google Maps loads...
			this.$refs.map.$mapPromise.then(() => {
				// 1). get the Markers' data from the API
				var url = 'https://<api-server>/locator/v1/locations/' +
					this.distance +
					this.place.geometry.location.lat() +
					'/' +
					this.place.geometry.location.lng() +
					this.results +
					'/' +
					this.page
				axios.get(url).then(response => {
					this.markers = response.data
				})

				// 2). Set "mapLoad" = true so we can add the Markers
				this.mapLoaded = true
			})
		},

		// Set the location from user's input
		setPlace(place) {
			this.place = place
		}
	}
}
</script>
// Google Maps API key
const apiKey = "AIzaSyC6GptxgqmfObQrMTB3UfLoc40fi54bmKE";

// Function to load the Google Maps API
function loadGoogleMapsAPI() {
  // Creates a script element dynamically
  const script = document.createElement("script");

  // Sets the source attribute for the script element with the Google Maps API URL, including the API key and necessary libraries
  script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initMap`;

  // Defers script execution until the HTML content is fully loaded
  script.defer = true;

  // Loads the script asynchronously
  script.async = true;

  // Appends the script element to the head of the HTML document
  document.head.appendChild(script);
}

// Function to initiate the search
function submitSearch() {
  // Retrieves the value entered in the 'radius' input field
  const radius = document.getElementById("radius").value;

  // Uses the radius and geolocation to perform the search and display results
  navigator.geolocation.getCurrentPosition(
    // Success callback function when geolocation is obtained
    (position) => {
      const userLocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
      };

      // After obtaining results, navigates to the results page with user's location and search radius as parameters
      window.location.href = `results.html?lat=${userLocation.lat}&lng=${userLocation.lng}&radius=${radius}`;
    },
    // Error callback function when geolocation fails
    (error) => {
      console.error("Error getting user location:", error);
    }
  );
}

// Function to initiate the map on the results page
function initMap() {
  // Parses URL parameters to obtain user's latitude, longitude, and search radius
  const urlParams = new URLSearchParams(window.location.search);
  const userLat = parseFloat(urlParams.get("lat"));
  const userLng = parseFloat(urlParams.get("lng"));
  const radius = parseFloat(urlParams.get("radius"));

  // Defines options for the map, including center and zoom level
  const mapOptions = {
    center: { lat: userLat, lng: userLng },
    zoom: 12,
  };

  // Creates a new map object with specified options
  const map = new google.maps.Map(document.getElementById("map"), mapOptions);

  // Displays a marker at the user's location on the map
  new google.maps.Marker({
    position: { lat: userLat, lng: userLng },
    map: map,
    title: "Your Location",
    icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png", // Optional: Customized user marker icon
  });

  // Performs a Places API search for electronic stores near the user's location
  searchElectronicStores({ lat: userLat, lng: userLng }, map, radius);
}

// Function to perform a Places API search for electronic stores near a location
function searchElectronicStores(location, map, radius) {
  // Defines a request object for Places API search with specified parameters
  const request = {
    location: location,
    radius: radius * 1609.34, // Converts miles to meters due to Places requiring it to be so to function properly.
    types: ["electronics_store"],
  };

  // Creates a PlacesService object using the provided map
  const service = new google.maps.places.PlacesService(map);

  // Initiates a nearby search using the Places API with the defined request
  service.nearbySearch(request, (results, status) => {
    if (status === google.maps.places.PlacesServiceStatus.OK) {
      // If the search is successful, displays the results on the map
      displayResults(location, map, results);
    } else {
      // Logs an error message if the Places API request fails
      console.error("Places API request failed with status:", status);
    }
  });
}

// Function to display the results on the map
function displayResults(userLocation, map, results) {
  // Creates DirectionsService and DirectionsRenderer objects
  const directionsService = new google.maps.DirectionsService();
  const directionsRenderer = new google.maps.DirectionsRenderer();

  // Sets the map for rendering directions
  directionsRenderer.setMap(map);

  // Retrieves the container for displaying directions
  const directionsContainer = document.getElementById("directions-container");
  const infoWindow = new google.maps.InfoWindow();

  // Iterates through each place in the results
  results.forEach((place) => {
    // Creates a marker for each place on the map
    const marker = new google.maps.Marker({
      position: place.geometry.location,
      map: map,
      title: place.name,
    });

    // Adds a click event listener to show directions when a marker is selected
    marker.addListener("click", () => {
      // Defines a request object for directions from user's location to the selected place
      const request = {
        origin: userLocation,
        destination: place.geometry.location,
        travelMode: google.maps.TravelMode.DRIVING,
      };

      // Initiates a directions request using the DirectionsService
      directionsService.route(request, (response, status) => {
        if (status === google.maps.DirectionsStatus.OK) {
          // If directions are obtained successfully, renders them on the map
          directionsRenderer.setDirections(response);

          // Displays directions under the map as HTML
          const directionsText = response.routes[0].legs[0].steps
            .map((step) => step.instructions)
            .join("<br>");
          directionsContainer.innerHTML = `<div>${place.name}</div><div>${directionsText}</div>`;
        } else {
          // Logs an error message if the directions request fails
          console.error("Directions request failed with status:", status);
        }
      });
    });
  });
}

// Function to show driving directions from the user's location to a selected store
function showDirections(userLat, userLng, storeLat, storeLng) {
  // Creates DirectionsService and DirectionsRenderer objects
  const directionsService = new google.maps.DirectionsService();
  const directionsRenderer = new google.maps.DirectionsRenderer();

  // Defines options for the map, including center and zoom level
  const mapOptions = {
    center: { lat: userLat, lng: userLng },
    zoom: 12,
  };

  // Creates a new map object with specified options
  const map = new google.maps.Map(document.getElementById("map"), mapOptions);

  // Sets the map for rendering directions
  directionsRenderer.setMap(map);

  // Defines a request object for directions from user's location to the selected store
  const request = {
    origin: { lat: userLat, lng: userLng },
    destination: { lat: storeLat, lng: storeLng },
    travelMode: google.maps.TravelMode.DRIVING,
  };

  // Initiates a directions request using the DirectionsService
  directionsService.route(request, (response, status) => {
    if (status === google.maps.DirectionsStatus.OK) {
      // If directions are obtained successfully, renders them on the map
      directionsRenderer.setDirections(response);
    } else {
      // Logs an error message if the directions request fails
      console.error("Directions request failed with status:", status);
    }
  });
}

// Calls the loadGoogleMapsAPI function when the document is fully loaded
document.addEventListener("DOMContentLoaded", loadGoogleMapsAPI);



// Function to submit Flickr search
function submitFlickrSearch() {
  // Retrieve the keyword entered in the form
  const keyword = document.getElementById("keyword").value;

  // Check if the keyword is not empty
  if (keyword.trim() !== "") {
    // Call the function to fetch Flickr images
    fetchFlickrImages(keyword);
  } else {
    alert("Please enter a keyword before submitting.");
  }
}

// Function to fetch Flickr images
function fetchFlickrImages(keyword) {
  // Flickr API key
  const flickrApiKey = "ccbf01554dde93266b137c5202440d76";

  // Flickr API endpoint for photos search
  const flickrApiEndpoint = "https://www.flickr.com/services/rest/?method=flickr.photos.search";

  // Flickr API request parameters
  const flickrSearchParams = `&api_key=${flickrApiKey}&text=${encodeURIComponent(keyword)}&format=json&nojsoncallback=1&per_page=10`;

  // Constructs the full URL for the Flickr API request
  const flickrApiUrl = `${flickrApiEndpoint}${flickrSearchParams}`;

  // Makes the API request using fetch
  fetch(flickrApiUrl)
    .then(response => response.json())
    .then(data => displayFlickrResults(data.photos.photo))
    .catch(error => console.error("Error fetching Flickr images:", error));
}

// Function to display Flickr results
function displayFlickrResults(photos) {
  const flickrResultsContainer = document.getElementById("flickrResults");

  // Clear previous results
  flickrResultsContainer.innerHTML = "";

  // Iterate through each photo and display it
  photos.forEach(photo => {
    const imgSrc = `https://farm${photo.farm}.staticflickr.com/${photo.server}/${photo.id}_${photo.secret}_m.jpg`;

    const imgElement = document.createElement("img");
    imgElement.src = imgSrc;
    imgElement.alt = photo.title;

    // Attach a click event to view larger image
    imgElement.addEventListener("click", () => viewLargerImage(photo));

    flickrResultsContainer.appendChild(imgElement);
  });

  // Store the search results in localStorage
  storeSearchResults(photos);
}

// Function to store search results in localStorage
function storeSearchResults(results) {
  // Convert the results to JSON and store in localStorage
  localStorage.setItem("flickrResults", JSON.stringify(results));
}

// Function to submit Flickr search
function submitFlickrSearch() {
  // Retrieve the keyword entered in the form
  const keyword = document.getElementById("keyword").value;

  // Check if the keyword is not empty
  if (keyword.trim() !== "") {
    // Call the function to fetch Flickr images
    fetchFlickrImages(keyword);
  } else {
    alert("Please enter a keyword before submitting.");
  }
}

// Function to fetch Flickr images
function fetchFlickrImages(keyword) {
  // Flickr API key
  const flickrApiKey = "ccbf01554dde93266b137c5202440d76";

  // Flickr API endpoint for photos search
  const flickrApiEndpoint = "https://www.flickr.com/services/rest/?method=flickr.photos.search";

  // Flickr API request parameters
  const flickrSearchParams = `&api_key=${flickrApiKey}&text=${encodeURIComponent(keyword)}&format=json&nojsoncallback=1&per_page=10`;

  // Construct the full URL for the Flickr API request
  const flickrApiUrl = `${flickrApiEndpoint}${flickrSearchParams}`;

  // Make the API request using fetch
  fetch(flickrApiUrl)
    .then(response => response.json())
    .then(data => displayFlickrResults(data.photos.photo))
    .catch(error => console.error("Error fetching Flickr images:", error));
}

// Function to view a larger image
function viewLargerImage(photo) {
  const largerImgSrc = `https://farm${photo.farm}.staticflickr.com/${photo.server}/${photo.id}_${photo.secret}_b.jpg`;

  // Open the larger image URL in a new tab or window
  window.open(largerImgSrc, "_blank");
}

document.addEventListener("DOMContentLoaded", () => {
  // Retrieve search results from localStorage
  const storedResults = localStorage.getItem("flickrResults");

  if (storedResults) {
    // If results are found, parse and display them
    const parsedResults = JSON.parse(storedResults);
    displayFlickrResults(parsedResults);
  } else {
    // If no results found
    console.log("No stored results found.");
  }
});
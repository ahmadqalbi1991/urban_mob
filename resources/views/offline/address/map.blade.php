<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKLanPjE7CjC12KjCCiG5Y-7AG58UuC1M"></script> -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHrKkhwSDWrr45yqGAt2GjgF0adHLAkTU"></script>

  <!--  -->
  <style>
    #map {
      height: 200px;
      margin-bottom: 2%;
    }
  </style>
  <div id="map"></div>
  <button onclick="getLiveLocation()" type="button" class="btn btn-warning btn-sm getlocation d-none">Get Live Location</button>
  <br>
   <input type="hidden" class="form-control" id="latitude" name="lat"  readonly>
    <input type="hidden" class="form-control" id="longitude" name="long" readonly>
  
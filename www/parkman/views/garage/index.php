<?php
/* @var $this yii\web\View */
use app\assets\MapAsset;
MapAsset::register($this);
?>
<br>
<br>

<div class="row">
    <!-- Forms -->
    <div class="col-md-5" id="forms">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#countryTab">Search By Country</a></li>
            <li><a data-toggle="tab" href="#locationTab">Search By Location</a></li>
            <li><a data-toggle="tab" href="#ownerTab">Search By Owner</a></li>
        </ul>
        <br>

        <div class="tab-content">

            <div id="locationTab" class="tab-pane fade">
                <h3>By Location</h3>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3"> Latitude : </div>
                    <div class="col-lg-8 col-md-8 col-sm-3"><input type="text" name="latitude" id="latitude" value=""></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3"> Longitude : </div>
                    <div class="col-lg-8 col-md-8 col-sm-3"><input type="text" name="longitude" id="longitude" value=""></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3"> Proximity : </div>
                    <div class="col-lg-8 col-md-8 col-sm-3"><input type="text" name="proximity_geo" id="proximity_geo" value=""></div>
                </div>

                <br>
                <div class="row submit-div">
                    <div class="col-md-12">
                        <button class="btn-warning" onclick="getByLatLng()">Get Garages</button>
                    </div>
                </div>
            </div>

            <div id="ownerTab" class="tab-pane fade">
                <h3>By Owner</h3>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3"> Owner Name : </div>
                    <div class="col-lg-8 col-md-8 col-sm-3"><input type="text" name="owner" id="owner"></div>
                </div>
                <br>
                <div class="row submit-div">
                    <div class="col-md-12">
                        <button class="btn-warning" onclick="getByOwner()">Get Garages</button>
                    </div>
                </div>
            </div>

            <div id="countryTab" class="tab-pane fade in active">
                <h3>By Country</h3>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3"> Country : </div>
                    <div class="col-lg-8 col-md-8 col-sm-3"><input type="text" name="country" id="country" value="Germany"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3"> Proximity : </div>
                    <div class="col-lg-8 col-md-8 col-sm-3"><input type="text" name="proximity_country" id="proximity_country" value="100"></div>
                </div>
                <br>
                <div class="row submit-div">
                    <div class="col-md-12">
                        <button class="btn-warning" onclick="getByCountry()">Get Garages</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Center divide -->
    <div class="col-md-1">
        <div class="vertical-line" /></div>
    </div>

    <!-- MAPS DIV -->
    <div class="col-md-6">
        <div id="map" style="width: 500px; height: 400px;"></div>
    </div>

</div>

<br>

<div class="row" >
    <div class="col-md-11">
        <pre id="display-json"></pre>
    </div>
</div>


<script>
    getByCountry();
</script>

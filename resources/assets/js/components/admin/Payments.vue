<template>
    <section>
        <article class="article-page container-768">
            <article-close v-bind:link="URL.base+'/admin-dashboard'"></article-close>
            <h2 class="article-title mb-5">Payments</h2>
            <div class="container-640">
                <div class="row" data-padding="2">
                    <div class="col">
                        <div class="form-group-calendar">
                            <input id="startDate" type="text" class="form-control form-control-md min-width-160" placeholder="Start Date..." />
                            <span class="right-icon fa fa-calendar"></span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group-calendar">
                            <input id="endDate" type="text" class="form-control form-control-md min-width-160" placeholder="End Date..." />
                            <span class="right-icon fa fa-calendar"></span>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <br />
            <h4 class="article-title" v-sup="totalPrice" data-suffix=" Total Paid"></h4>
            <br />
            <br />
            <div class="div-select right-radius container-440 mb-3">
                <select @change="filterChange" v-model="filter" class="custom-select custom-select-lg">
                    <option selected="selected" hidden="hidden" disabled="disabled" value="null">Filter payments by...</option>
                    <option value="date_desc">Date recent first</option>
                    <option value="date_asc">Date oldest first</option>
                    <option value="total_desc">Total highest first</option>
                    <option value="total_asc">Total lowest first</option>
                    <option value="name_asc">Captain A to Z</option>
                    <option value="name_desc">Captain Z to A</option>
                </select>
                <button type="button" class="btn btn-primary dropdown-toggle">
                </button>
            </div>
            <br />
            <div class="container-640">
                <table class="table table-borderless table-striped font-opensans">
                    <thead>
                        <tr>
                            <th>DATE</th>
                            <th>CAPTAIN</th>
                            <th>TRIP</th>
                            <th>TOTAL</th>
                            <th>CAPT PAY</th>
                            <!-- <th>METHOD</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="trip in tripList">
                            <td>{{ trip.date }}</td>
                            <td class="text-break">{{ trip.firstName }} {{ trip.lastName }}</td>
                            <td><a :href="URL.base + '/' + trip.tripId + '/admin-trip-detail'" class="link-underline">#{{ trip.tripId }}</a></td>
                            <td v-sup="trip.total"></td>
                            <td v-sup="trip.payment"></td>
                            <!-- <td>{{ trip.payment_method }}</td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
            <br />
            <br />
            <div class="text-center" v-if="totalCount > tripList.length">
                <a @click.prevent="getMoreTrip" href="#" class="btn btn-darkblue btn-size-360">Show More Payments</a>
            </div>
            <br />
        </article>
        <working-indicator v-if="working"></working-indicator>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                URL: window.URL,
                totalCount: 0,
                totalPrice: 0,
                tripList: [],
                filter: null,
                startDate: null,
                endDate: null,
                working: false
            }
        },
        mounted() {  
            var vm = this
            this.$nextTick(function () {
                $('#startDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.startDate == e.target.value)
                        return;
                    vm.startDate = e.target.value;
                    vm.tripList = [];
                    vm.getTripList();
                })
                $('#endDate').datetimepicker({
                    format: 'MM/DD/YYYY'
                })
                .on("dp.change", function(e) {
                    if(vm.endDate == e.target.value)
                        return;
                    vm.endDate = e.target.value;
                    vm.tripList = [];
                    vm.getTripList();
                })
                this.getTripList();
            })          
        },

        created() {
        },

        methods: {
            getTripList() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    limit: this.tripList.length
                }
                this.$http.post(this.URL.base + '/admin-payment-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    vm.totalPrice = responseData.totalPrice;
                    vm.tripList = responseData.tripList;
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            getMoreTrip() {
                this.working = true;
                var vm = this;
                var param = {
                    startDate: this.startDate,
                    endDate: this.endDate,
                    filter: this.filter,
                    offset: this.tripList.length
                }
                this.$http.post(this.URL.base + '/admin-payment-list', param, {})
                .then(response => {
                    var responseData = response.body;
                    vm.totalCount = responseData.totalCount;
                    vm.totalPrice = responseData.totalPrice;
                    responseData.tripList.forEach(item => {
                        vm.tripList.push(item);
                    })
                    this.working = false;
                })
                .catch(error => {
                    this.working = false;
                })
            },

            filterChange() {
                this.getTripList();
            }
        },
    }
</script>

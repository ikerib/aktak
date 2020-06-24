import Vue from 'vue';
import Liburua from './components/liburua/Liburua';

import moment from 'moment'

Vue.filter("formatDate", function ( value ) {
    if ( value ) {
        return moment(String(value)).format("DD/DD/YYYY HH:MM:SS");
    }
});

const app = new Vue({
    el: '#app',
    render: h => h(Liburua)
});

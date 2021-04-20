const $ = require('jquery');
global.$ = global.jQuery = $;

import moment from "moment";
window.moment = moment;

import Swal from "sweetalert2";

require('bootstrap');
require('datatables.net-bs4');
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import "typeahead.js";
import "bootstrap-datepicker"
import "bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min"
import "bootstrap-datepicker/dist/locales/bootstrap-datepicker.eu.min"

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

require('select2');
import '../css/app.scss';

$(document).ready(function () {
    $(".datepicker").datepicker({
        // format:  'yyyy-mm-ddT22:12:34.000Z',
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        language: "eu",
        autoclose: true,
        todayHighlight: true
    });
    $('[data-toggle="popover"]').popover();

    $('.myselect2').select2();
    const appLocale = $('#app_locale').val();
    const datatablesLocaleURL = "/build/datatables/" + appLocale + ".json";

    $('.mydatatable').DataTable({
        language: {
            url: 'eu'
        },
        "paging": true,
        "ordering": true,
        "info": true,
        "bLengthChange": true,
        "order": []
    });

    // Utils
    $('body').on('click', '.btnRemoveRow', function () {
        const that = $(this);
        Swal.fire({
            title: 'Ziur zaude?',
            text: "Ezin izango duzu berreskuratu onartuz gero.",
            type: 'warning',
            animation: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Bai, ezabatu!'
        }).then((result) => {
            if (result.value) {
                console.log(result.value);
                $(that).closest('form').submit();
            }
        });
    }).on('click', '.btnSave', function () {
        $('.btnSubmitButton').click()
    });

    $(".cmdGarbituLiburuaSearchForm").on("click", function () {
        $("#liburua_search_form_kodea").val("");
        $("#liburua_search_form_hasieratik").datepicker('update', '');
        $("#liburua_search_form_hasierara").datepicker('update', '');
        $("#liburua_search_form_amaieratik").datepicker('update', '');
        $("#liburua_search_form_amaierara").datepicker('update', '');
        $("#liburua_search_form_egoera").val("");
        $("#liburua_search_form_mota").val("");
    });

    $(".cmdGarbituErabakiaSearchForm").on("click", function () {
        $("#erabakia_search_form_testua").val("");
        $("#erabakia_search_form_datatik").datepicker('update', '');
        $("#erabakia_search_form_datara").datepicker('update', '');
        $("#erabakia_search_form_liburua").val("");
    });

    $('.selectBook').on('click', function () {
        const liburuaid = $(this).data("liburuaid");
        $("#erabakia_search_form_liburua").val(liburuaid);
        $("#searchForm").submit();
    })

    $('.btnBilatu').on("click", function () {
        console.log("froga");
        document.getElementById('searchForm').submit();
        return false;
    });

    // Highlight-a egiteko eremua
    const $box = document.getElementById('erabakiakTable');
    // biilaketa testua
    let searchText = $('#erabakia_search_form_testua').val();
    // kendu karaktere bereziak +,- eta horrelakoak
    searchText = searchText.replace(/[^a-zA-Z ]/g, "");
    if (searchText.length > 0) {
        let terms = searchText.split(" ");
        terms.forEach(function(term){
            const regex = new RegExp(term, 'gi');
            let text = $box.innerHTML;
            // text = text.replace(/(<mark class="highlight">|<\/mark>)/gim, '');
            const newText = text.replace(regex, '<mark class="highlight">$&</mark>');
            $box.innerHTML = newText;
        });

    }

});


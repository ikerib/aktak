<template>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Kodea</th>
                <th>Hasiera</th>
                <th>Amaiera</th>
            </tr>
        </thead>
        <tr v-for="liburua in this.dataLiburuak">
            <td>{{ liburua.id }}</td>
            <td>{{ liburua.kodea }}</td>
            <td>{{ getHumanDate(liburua.hasiera) }}</td>
            <td>{{ getHumanDate(liburua.amaiera) }}</td>
        </tr>
    </table>
</template>
<script>
    import axios from "axios";
    import moment from "moment";

    export default {
        name: 'liburua',
        data() {
            return {
                dataLiburuak:{}
            }
        },
        methods:{
            getLiburuak() {
                axios.get("/api/liburuas.json")
                     .then(response => {
                         this.dataLiburuak = response.data;
                     })
                     .catch(error => console.log(error));
            },
            getHumanDate : function (date) {
                return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
            }
        },
        created() {
            this.getLiburuak();
        }
    }
</script>

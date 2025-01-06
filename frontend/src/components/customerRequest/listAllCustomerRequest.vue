<template>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">
                    <span class="d-none d-md-block">Nº</span>
                    <span class="d-md-none">Nº</span>
                </th>
                <th scope="col">
                    <span class="d-none d-md-block">Preço</span>
                    <span class="d-md-none">$</span>
                </th>
                <th scope="col">
                    <span class="d-none d-md-block">Data</span>
                    <span class="d-md-none">Data</span>
                </th>
                <th scope="col">Status</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <template v-for="(v, i) in getAllCustomerRequest?.data" :key="i">
                <tr>
                    <th scope="row">{{ Number(v.id) < 10 ? '0' + Number(v.id) : v.id }}</th>
                            <template v-if="v.price_promo && (v.price_promo < v.price)">
                    <td><span class="text-decoration-line-through me-3">{{ v.price }}</span><span>{{ v.price_promo
                            }}</span></td>
            </template>
            <template v-else>
                <td>{{ v.price }}</td>
            </template>
            <td>{{ v.created_at }}</td>
            <td>{{ v.status }}</td>
            <td><router-link class="btn btn-outline-secondary" title="Clique para Ver o Item"
                    :to="{ name: 'dashboardCustomerRequestShow', params: { id: v.id } }">
                    <i class="bi bi-eye-fill"></i>
                </router-link>
                <!-- <a :href="'/dashboard/customer-request/'+v.id"><i class="bi bi-eye-fill"></i></a> -->
            </td>
            </tr>
</template>
</tbody>
</table>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'ListAllCustomerRequest',
    data() {
        return {
            varSetIcon: false,
        }
    },
    props: {
        enableBtnDelete: null
    },
    computed: {
        ...mapGetters('customerRequest', ['getAllCustomerRequest']),
    },
    mounted() {
        this.init();
    },
    methods: {
        ...mapActions('customerRequest', ['all']),

        init() {
            // if (!this.getAllCustomerRequest) {
            //     return this.all();
            // }

            return this.all();
        },
    }
}
</script>

<style scoped lang="scss"></style>
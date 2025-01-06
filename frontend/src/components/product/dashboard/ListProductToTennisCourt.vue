<template>
    <section>
        <div class="d-flex flex-column justify-content-center">

            <table v-if="getProductsItemsToTennisCourtId?.success" class="table">
                <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Unidade</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Promo</th>
                        <th scope="col">CMD</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(vDados, i) in getProductsItemsToTennisCourtId?.data" :key="i">
                        <tr>
                            <th scope="row">{{ vDados.products_default.name }}</th>
                            <td>{{ vDados.products_default.unit }}</td>
                            <td>$ {{ vDados.price }}</td>
                            <td>$ {{ vDados.price_promo ?? '-' }}</td>
                            <td>                                
                                <button @click.prevent="this.$router.push({name:'dashboardProductEdit', params:{id:vDados.id}, query:{redirect, tennis_court_id:tennis_court_id}})" type="button" class="btn text-primary"><i
                                        class="bi bi-pencil"></i></button>
                                <button @click.prevent="this.$store.dispatch('product/deleteItem', vDados)" type="button" class="btn text-danger"><i
                                        class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </section>

</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'ListProductToTennisCourt',
    data() {
        return {
            tennis_court_id: this.$route.query.tennis_court_id ?? null,
            redirect:null,
        }
    },
    props: {
        tennisCourtId: [Number, String],
    },
    async mounted() {
        await this.init();
    },
    computed: {
        ...mapGetters('product', ['getProductsItemsToTennisCourtId']),
    },
    methods: {
        ...mapActions('product', ['productsItemsToTennisCourtId']),

        /// 
        async init() {
            this.redirect = '/dashboard/product/create/?tennis_court_id='+this.tennis_court_id
            return await this.productsItemsToTennisCourtId(this.tennisCourtId)
        },
    },
}
</script>

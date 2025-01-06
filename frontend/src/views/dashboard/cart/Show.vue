<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container-fluid py-5 h-100">
                    <template v-if="getTotalOfItemsIntoCart">
                        <div class="row align-items-start">
                            <div class="col-md-6">
                                <table class="table table-hover table-responsive">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">
                                                <span class="d-none d-md-block">Produto</span>
                                                <span class="d-md-none">Pro</span>
                                            </th>
                                            <!-- <th scope="col">
                                                <span class="d-none d-md-block">Quantidade</span>
                                                <span class="d-md-none">Qtd</span></th> -->
                                            <th scope="col">
                                                <span class="d-none d-md-block">Preço</span>
                                                <span class="d-md-none">$</span></th>
                                            <!-- <th scope="col">
                                                <span class="d-none d-md-block">Desconto</span>
                                                <span class="d-md-none">Desc</span></th> -->
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(v, i) in getItemsCart" :key="i">
                                            <tr>
                                                <th scope="row">{{ Number(i) < 10 ? '0' + (Number(i) + Number(1)) : i}}</th>
                                                <!-- <td @click="viewDetail(v.tennis_court_calendar_id ? 'tennis_court_calendar' : 'product', v.id)" :id="'cardLineShow'+v.id" role="button">{{ showMaxWordsIntoTag(v.product_name, 30000, 'cardLineShow'+v.id) }}</td> -->
                                                <td @click="viewDetail(v.tennis_court_calendar_id ? 'tennis_court_calendar' : 'product', v.id)" :id="'cardLineShow'+v.id" role="button">{{ v.product_name }}</td>
                                                <!-- <td>{{ v.qty }}</td> -->
                                                <td>{{ v.price_promo ? `<span class="text-decoration-line-through me-3">${v.price}</span><span>${v.price_promo}</span>` : v.price }}</td>
                                                <!-- <td>{{ v.discount ?? '0,00' }}</td> -->
                                                <td><button class="btn text-danger" title="Excluir Item" @click.prevent="this.$store.dispatch('cart/deleteItem', v.id)"><i class="bi bi-x"></i></button></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <!-- <div class="d-flex flex-column justify-content-end align-items-center align-self-center mb-2"> -->
                                <div class="border rounded py-5" style="background-color: #f3f3f3;">
                                    
                                    <listPaymentMethods></listPaymentMethods>
                                    
                                </div>
                            </div>
                        </div>

                    </template>

                    <template v-else>
                        {{ getItemsCart?.response?.data?.message ?? 'Carrinho Vazio' }}
                    </template>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import helpers from '@/helpers/helpers';
import { defineAsyncComponent } from 'vue';
import { mapGetters } from 'vuex';

export default {
    name: 'TennisCourtShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        listPaymentMethods: defineAsyncComponent(() =>
            import('@/components/cart/dashboard/listPaymentMethods.vue')
        ),
    },
    data() {
        return { 
            register: {},
            //id: this.$route.params.id,
            //endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
        }
    },
    computed: {
        ...mapGetters('cart', ['getItemsCart', 'getValueTotalOfCart', 'getTotalOfItemsIntoCart']),
    },
    mounted() {
        this.init();
    },
    methods: {
        ///
        async init() {
        },

        ///
        async showMaxWordsIntoTag(v){
            return helpers.showMaxWordsIntoTag(v);
        }
    }
}
</script>
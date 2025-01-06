<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row border-1 border-bottom mb-4 ">
                        <h1>Pedido nº: {{ getCustomerRequest?.id < 10 ? '0' + getCustomerRequest?.id :
                            getCustomerRequest?.id }}</h1>
                    </div>

                    <div class="row align-items-start">
                        <div class="col-md-6">
                            <listItemsIntoCard :items="getCustomerRequest"></listItemsIntoCard>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="border rounded p-5" style="background-color: #f3f3f3;">
                                <template v-if="getCustomerRequest?.payment_method?.type == 'pix'">
                                    <!-- <pixPaymentMethodSelected :item="getCustomerRequestPaymentSelected" :checkPaymentIsMade="getCheckPaymentIsMade"></pixPaymentMethodSelected> -->
                                    <pixPaymentMethodSelected></pixPaymentMethodSelected>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
//import Api from '@/services/Api';
import ErrorComponent from '@/components/itemsAsyncComponent/ErrorComponent.vue';
import LoadingComponent from '@/components/itemsAsyncComponent/LoadingComponent.vue';
import { defineAsyncComponent } from 'vue';
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'CustomerRequestShow',
    components: {
        templateView: defineAsyncComponent({
            loader: () => import('@/views/template/Template.vue'),
            loadingComponent: LoadingComponent,
            error: ErrorComponent,
            timeout: 3000
        }),
        // pixPaymentMethodSelected: defineAsyncComponent(() =>
        //     import('@/components/customerRequest/pixPaymentMethodSelected.vue')
        // ),
        pixPaymentMethodSelected: defineAsyncComponent({
            loader: () => import('@/components/customerRequest/pixPaymentMethodSelected.vue'),
            loadingComponent: LoadingComponent,
            error: ErrorComponent,
            timeout: 3000
        }),
        listItemsIntoCard: defineAsyncComponent({
            loader: () => import('@/components/cart/dashboard/listItemsIntoCard.vue'),
            loadingComponent: LoadingComponent,
            error: ErrorComponent,
            timeout: 3000
        }),
    },
    data() {
        return {
            register: {},
            //endPointStorage: `${process.env.VUE_APP_API_URL_STORAGE}`,
            //id: this.$route.params.id ?? null,
        }
    },
    computed: {
        ...mapGetters('customerRequest', ['getCustomerRequest', 'getCustomerRequestPaymentSelected', 'getCheckPaymentIsMade']),
    },
    async mounted() {
        await this.init();
    },
    methods: {
        ...mapActions('customerRequest', ['show']),

        async init() {
            if (!this.getCustomerRequest || this.getCustomerRequest?.id != this.$route.params.id) {
                await this.show(this.$route.params.id);
                //console.log('revalidou o getCustomerRequest', this.getCustomerRequest)
            }
            return;
        },
    }
}
</script>

<style scoped lang="scss"></style>
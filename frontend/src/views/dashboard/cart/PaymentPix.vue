<template>
    <templateView>
        <template v-slot:slotPageComponet>
            <section>
                <div class="container py-5 h-100">
                    <div class="row border-1 border-bottom mb-4 ">
                        <h1>
                            Pedido nº: {{ getCustomerRequest.id < 10 ? '0' + getCustomerRequest.id :
                                getCustomerRequest.id }} </h1>
                    </div>

                    <div class="row align-items-start">
                        <div class="col-md-6">
                            <listItemsIntoCard :items="getCustomerRequest.cart"></listItemsIntoCard>
                            <div class="me-3 fs-4 text-start my-3">Total: R$ {{ getCustomerRequest.price ?? "A SOMAR" }}</div>
                            <div class="me-3 fs-4 text-start my-3">Total: R$ {{ getCustomerRequest.price_promo ?? "" }}</div>

                        </div>
                        <div class="col-md-6 text-center">
                            <div class="border rounded p-5" style="background-color: #f3f3f3;">
                                <div>
                                    <button type="button" class="btn btn-lg" @click.prevent="copyQrCode()">Copiar Código
                                        <i id="icon_copy_qr_code_default" class="bi bi-copy"></i>
                                    </button>

                                    <span class="copyAlert d-none border rounded p-2 border-2 bg-success-subtle">
                                        <span class="fst-italic text-success">(Copiado)</span> <i
                                            id="icon_copy_qr_code_active" class=" bi bi-check2-all text-success"></i>
                                    </span>

                                </div>

                                <div>
                                    <img :src="getCustomerRequestPaymentMethodSelected.imagemQrcode" alt="Pix QrCode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
    </templateView>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { mapGetters } from 'vuex';

export default {
    name: 'TennisCourtShow',
    components: {
        templateView: defineAsyncComponent(() =>
            import('@/views/template/Template.vue')
        ),
        listItemsIntoCard: defineAsyncComponent(() =>
            import('@/components/cart/dashboard/listItemsIntoCard.vue')
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
        // ...mapGetters('cart', ['getTotalOfItemsIntoCart', 'getCartPaymentMethodSelected']),
        ...mapGetters('customerRequest', ['getCustomerRequest', 'getCustomerRequestPaymentMethodSelected']),
    },
    mounted() {
        this.init();
    },
    methods: {
        async init() {
            console.log('getCustomerRequest', this.getCustomerRequest)
            console.log('getCustomerRequestPaymentMethodSelected', this.getCustomerRequestPaymentMethodSelected)
        },

        async copyQrCode() {

            navigator.clipboard.writeText(this.getCustomerRequestPaymentMethodSelected.qrcode);

            const copyDiv = document.querySelector('.copyAlert:not(.animate)');
            const iconDefault = document.querySelector('#icon_copy_qr_code_default');

            if (copyDiv) {
                iconDefault.classList.add('d-none')
                copyDiv.classList.remove('d-none')
                copyDiv.classList.add('animate');
                //copyDiv.addEventListener('animationend', () => copyDiv.classList.remove('animate'));
            }
            return;
        },
    }
}
</script>

<style scoped lang="scss"></style>
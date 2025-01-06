<template>

    <template v-if="selfItem === undefined">
        <i class="bi bi-info-circle me-2"></i> Os dados não carregaram corretamente.
    </template>

    <template v-if="!selfItem && selfItem !== undefined"><button class="btn btn-link" @click.prevent="init">
            <i class="bi bi-arrow-clockwise me-2"></i>(recarregar)</button>
    </template>

    <template v-else>

        <div>
            <template v-if="getCheckPaymentIsMade">
                <div class="bg-success text-white rounded p-4">
                    <i class="bi bi-check2-circle my-3 fs-1"></i>
                    <p>Pix Realizado com Sucesso!</p>
                </div>

                <p class="text-start">
                    <strong>E2E: </strong>{{ getCustomerRequest?.payment_method?.payment_selected?.end_to_end_id }}
                    <br>
                    <strong>Criação: </strong>{{ getCustomerRequest?.payment_method?.payment_selected?.created_at }}
                    <br>
                    <strong>Atualização: </strong>{{ getCustomerRequest?.payment_method?.payment_selected?.updated_at }}
                </p>
            </template>
            <template v-else>
                <div>

                    <button type="button" class="btn btn-lg" @click.prevent="copyQrCode()">Copiar Código <i
                            id="icon_copy_qr_code_default" class="bi bi-copy"></i></button>

                    <span class="copyAlert d-none border rounded p-2 border-2 bg-success-subtle">
                        <span class="fst-italic text-success">(Copiado)</span>
                        <i id="icon_copy_qr_code_active" class=" bi bi-check2-all text-success"></i>
                    </span>

                    <div class="my-2">
                        Você ja pagou? <button @click.prevent="thisCheckPaymentIsMade(selfItem.txid)" type="button"
                            class="btn btn-success btn-sm animate__animated animate__headShake animate__infinite">
                            Clique e Confirme <i class="bi bi-hand-index-thumb"></i>
                        </button>

                        <p v-if="msgPixNotReceive" class="mt-2 bg-warning-subtle o-2">
                            <i class="bi bi-exclamation-circle me-2"></i> {{ msgPixNotReceive }}
                        </p>
                    </div>

                    <div>
                        <img :src="selfItem.image" alt="Pix QrCode" class="mb-3">
                        <countDownComponent :dateEnd="selfItem?.expires_in"></countDownComponent>
                    </div>
                </div>
            </template>
        </div>
    </template>
</template>

<script>
//import LaravelEcho from '@/services/LaravelEcho';
import helpers from '@/helpers/helpers';
import { defineAsyncComponent } from 'vue';
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'PixPaymentMethodSelected',
    components: {
        countDownComponent: defineAsyncComponent(() =>
            import('@/components/CountDownComponent.vue')
        ),
    },
    data() {
        return {
            register: {},
            msgPixNotReceive: '',
            selfItem: null,
            selfCheckPaymentIsMade: null,
        }
    },
    props: {
        checkPaymentIsMade: null,
    },
    computed: {
        ...mapGetters('customerRequest', ['getCustomerRequestPaymentSelected', 'getCheckPaymentIsMade', 'getCustomerRequest']),
    },
    async mounted() {
        this.init();
        this.broadcastPaymentPixIsPay();

        console.log('this.selfItem', this.selfItem);
        console.log('this.getCustomerRequest', this.getCustomerRequest);

    },
    methods: {
        ...mapActions('customerRequest', [
            'checkPaymentPixIsMade',
            'show',
            'broadcastPaymentPixIsPay'
        ]),

        ///
        async init() {
            if (!this.getCustomerRequestPaymentSelected) {
                //console.log('chamou PixPaymentMethodSelected');
                await this.show(this.$route.params.id);
                await this.broadcastPaymentPixIsPay();
                //console.log('this.selfItem dentro do if', this.selfItem);
            }

            this.selfItem = this.getCustomerRequestPaymentSelected
            //console.log('this.selfItem FORA do if', this.selfItem);
            //console.log('selfItem', this.selfItem);
            return;
        },

        ///
        async copyQrCode() {
            return await helpers.copyToClipboard(this.selfItem.qrcode).then(() => {
                //const copyDiv = document.querySelector('.copyAlert:not(.animate)');
                const copyDiv = document.querySelector('.copyAlert');
                //const iconDefault = document.querySelector('#icon_copy_qr_code_default');
                if (copyDiv) {
                    //iconDefault.classList.add('animate__animated', 'animate__swing', 'animate__delay-2s');
                    //iconDefault.classList.add('d-none')
                    copyDiv.classList.remove('d-none')
                    //copyDiv.classList.add('animate__animated','animate__swing', 'animate__delay-2s', 'animate__repeat-2');
                    //copyDiv.addEventListener('animationend', () => copyDiv.classList.remove('animate'));
                }
            });
        },

        ///
        async thisCheckPaymentIsMade(txid) {

            let r = await this.checkPaymentPixIsMade(txid);
            console.log('chamou', r);
            if (!r) {
                this.msgPixNotReceive = 'Ainda não recebemos. Aguarde alguns segundos e clique confirmando.';
            }
        }
    }
}

</script>
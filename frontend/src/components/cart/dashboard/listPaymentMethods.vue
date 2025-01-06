<template>

<div v-if="!getPaymentMethodsEnable?.data"><button class="btn btn-link" @click.prevent="init"><i class="bi bi-arrow-clockwise"></i>(recarregar)</button></div>

    <form @submit.prevent="formSubmit()">
        <template v-if="getPaymentMethodsEnable?.data">
            <template v-for="(v, i) in getPaymentMethodsEnable.data" :key="i">
                <div class="radio form-radio my-3" v-if="v.id">
                    <label class="label-payment-method " :for="`paymentMethodId${v.id}`" :data-payment-type="v.type"
                        :data-payment-text="v.name">
                        <input required type="radio" :value="v.id" :name="`payment_method_id`"
                            :id="`paymentMethodId${v.id}`" v-model="register.payment_method_id">

                    </label>
                </div>
            </template>
        </template>

        <div class="me-3 fs-4 text-end my-3">Total: R$ {{ getValueTotalOfCart ?? "A SOMAR" }}</div>

        <div class="row">
            <button type="submit" class="btn btn-success"><i class="bi bi-currency-dollar me-2"></i>Pagar</button>
        </div>
    </form>

</template>

<script> 
import { mapActions, mapGetters } from 'vuex';
import store from '@/store';

export default {
    name: 'ListPaymentMethods',
    data() {
        return {
            varSetIcon: false,
            register: {},
        }
    },
    computed: {
        ...mapGetters('cart', ['getValueTotalOfCart']),
        ...mapGetters('paymentMethod', ['getPaymentMethodsEnable']),
    },
    mounted() {
        this.init();
    },
    methods: {
        ...mapActions('paymentMethod', ['functionGetPaymentMethodsEnable']),

        ///
        async init() {
            //return await store.dispatch('paymentMethod/functionGetPaymentMethodsEnable').then(() => {
                return this.functionGetPaymentMethodsEnable().then(() => {
                this.setIcon();
            });
        },

        ///
        async setIcon() {
            if (this.varSetIcon) {
                return;
            }

            var allTags = document.querySelectorAll('.label-payment-method');
            for (let i = 0; i < allTags.length; i++) {
                var myText = allTags[i].getAttribute('data-payment-text');
                var myIcon = '<i class="bi bi-cash-coin mx-2"></i>' + myText;

                switch (allTags[i].getAttribute('data-payment-type')) {
                    case 'pix': myIcon = '<i class="fa-brands fa-pix mx-2"></i>' + myText;
                        break;
                    case 'cartao_de_credito': myIcon = '<i class="bi bi-credit-card mx-2"></i>' + myText;
                        break;
                    case 'boleto': myIcon = '<i class="bi bi-upc mx-2"> </i>' + myText;
                        break;
                }
                //allTags[i].querySelector('.icon-payment').innerHTML = myIcon;
                allTags[i].insertAdjacentHTML('afterend', myIcon);
            }
            this.varSetIcon = true;
        },

        ///
        async formSubmit() {

            let paymentMethodsItem = this.searchPaymentMethodsItem(this.getPaymentMethodsEnable.data, 'id', this.register.payment_method_id);
            return store.dispatch('cart/formSubmit',
                {
                    payment_method_id: paymentMethodsItem.id,
                    payment_method_type: paymentMethodsItem.type
                }
            );
        },

        searchPaymentMethodsItem(obj, key, value) {
            for (const [k, v] of Object.entries(obj)) {
                for (const [kk, vv] of Object.entries(v)) {
                    if (key == kk && value == vv) {
                        return obj[k];
                    }
                }
            }
            return
        }
    }
}
</script>

<style scoped lang="scss">
:root {
    --form-control-color: rebeccapurple;
}

*,
*:before,
*:after {
    box-sizing: border-box;
}

form {
    display: grid;
    place-content: center;
    /* min-height: 100vh; */
}

.form-control {
    line-height: 1.1;
    display: grid;
    grid-template-columns: 1em auto;
    gap: 0.5em;
}

.form-control+.form-control {
    margin-top: 1em;
}

.form-control:focus-within {
    color: var(--form-control-color);
}

input[type="radio"] {
    /* Add if not using autoprefixer */
    -webkit-appearance: none;
    /* Remove most all native input styles */
    appearance: none;
    /* For iOS < 15 */
    background-color: var(--form-background);
    /* Not removed via appearance */
    margin: 0;

    font: inherit;
    color: currentColor;
    width: 1.15em;
    height: 1.15em;
    border: 0.15em solid currentColor;
    border-radius: 50%;
    transform: translateY(-0.075em);

    display: grid;
    place-content: center;
}

input[type="radio"]::before {
    content: "";
    width: 0.65em;
    height: 0.65em;
    border-radius: 50%;
    transform: scale(0);
    transition: 120ms transform ease-in-out;
    box-shadow: inset 1em 1em var(--form-control-color);
    /* Windows High Contrast Mode */
    background-color: CanvasText;
}

input[type="radio"]:checked::before {
    transform: scale(1);
}

input[type="radio"]:focus {
    outline: max(2px, 0.15em) solid currentColor;
    outline-offset: max(2px, 0.15em);
}
</style>
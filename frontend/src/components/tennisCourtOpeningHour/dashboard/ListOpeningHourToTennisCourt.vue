<template>
    <section>
        <div class="d-flex flex-column justify-content-center">

            <table v-if="getTennisCourtOpeningHourItemsToTennisCourtId?.success" class="table">
                <thead>
                    <tr>
                        <th scope="col"><i class="bi bi-calendar-day me-2"></i></th>
                        <th scope="col">H Início</th>
                        <th scope="col">H Fim</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Tempo de expiração da compra</th>
                        <th scope="col">CMD</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(vDados, i) in getTennisCourtOpeningHourItemsToTennisCourtId?.data" :key="i">
                        <tr>
                            <th scope="row">{{ vDados.day }}</th>
                            <td>{{ vDados.hour_start }}:00</td>
                            <td>{{ vDados.hour_end }}:00</td>
                            <td>$
                                <template v-if="vDados.price_promo && (vDados.price_promo < vDados.price)">
                                <span class="text-decoration-line-through me-3">{{ vDados.price}}</span><span>{{ vDados.price_promo }}</span>
                                </template>
                                <template v-else>{{ vDados.price}}</template>
                            </td>
                            <td class="linePurchaseExpirationTime" :id="'linePurchaseExpirationTime' + vDados.id"
                                :value="vDados.purchase_expiration_time"></td>
                            <td>
                                <button @click.prevent="remove(vDados)" type="button" class="btn text-danger"><i
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
    name: 'ListOpeningHourToTennisCourt',
    data() {
        return {
        }
    },
    props: {
        tennisCourtId: [Number, String],
    },
    mounted() {
        this.init();
    },
    computed: {
        ...mapGetters('tennisCourtOpeningHour', ['getTennisCourtOpeningHourItemsToTennisCourtId']),
    },
    methods: {
        ...mapActions('tennisCourtOpeningHour', ['tennisCourtOpeningHourItemsToTennisCourtId', 'deleteItem']),

        ///
        async init() {
            await this.tennisCourtOpeningHourItemsToTennisCourtId(this.tennisCourtId ?? this.$route.params.id)
            await this.formatPurchaseExpirationTime();
            return;
        },

        ///
        async remove(id) {
            return await this.deleteItem(id)
        },

        ///
        async formatPurchaseExpirationTime() {
            const collection = document.getElementsByClassName("linePurchaseExpirationTime");
            for (let i = 0; i < collection.length; i++) {
                let v = collection[i].getAttribute('value');
                collection[i].innerHTML = v.substring(0, 5);
            }

            return;
        },
    },
}
</script>
